import React, { useState, useRef, useEffect } from 'react';
import { Button, ButtonProps } from "@/components/ui/button";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog";
import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Sparkles, Loader2, ThumbsUp, ThumbsDown, RotateCcw, Check, Pencil } from "lucide-react";
import axios, { CancelTokenSource } from 'axios';

interface AiGenerationDialogProps {
    localeCode: string;
    initialPrompt?: string;
    dialogTitle?: string;
    dialogDescription?: string;
    triggerButtonProps?: ButtonProps;
    triggerButtonContent?: React.ReactNode;
    onInsert: (generatedText: string) => void;
    onError?: (errorMessage: string) => void;
    onGenerationStart?: () => void;
    onGenerationSuccess?: () => void;
    generationMode?: 'stream' | 'json';
}

export function AiGenerationDialog({
    localeCode,
    initialPrompt = '',
    dialogTitle = "Generate with AI",
    dialogDescription = "Refine the prompt and generate content.",
    triggerButtonProps = { variant: "ghost", size: "icon" },
    triggerButtonContent = <Sparkles className="h-4 w-4" />,
    onInsert,
    onError = (msg) => console.error("AI Generation Error:", msg),
    onGenerationStart = () => {},
    onGenerationSuccess = () => {},
    generationMode = 'stream',
}: AiGenerationDialogProps) {
    const [isGenerating, setIsGenerating] = useState(false);
    const [isSubmittingFeedback, setIsSubmittingFeedback] = useState(false);
    const [feedbackSubmitted, setFeedbackSubmitted] = useState<'good' | 'bad' | null>(null);
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [prompt, setPrompt] = useState(initialPrompt);
    const [generatedText, setGeneratedText] = useState<string>('');
    const [viewMode, setViewMode] = useState<'prompt' | 'result'>('prompt');
    const eventSourceRef = useRef<EventSource | null>(null);
    const cancelTokenSourceRef = useRef<CancelTokenSource | null>(null);

    const openDialog = () => {
        setPrompt(initialPrompt || '');
        setGeneratedText('');
        setViewMode('prompt');
        setIsGenerating(false);
        setIsSubmittingFeedback(false);
        setFeedbackSubmitted(null);
        setIsDialogOpen(true);

        if (eventSourceRef.current) {
            eventSourceRef.current.close();
            eventSourceRef.current = null;
        }
        if (cancelTokenSourceRef.current) {
            cancelTokenSourceRef.current.cancel("Operation canceled by opening dialog.");
            cancelTokenSourceRef.current = null;
        }
    };

    const handleGenerate = async () => {
        if (!prompt) {
            onError("Please enter a prompt.");
            return;
        }

        if (eventSourceRef.current) {
            eventSourceRef.current.close();
            eventSourceRef.current = null;
        }
        if (cancelTokenSourceRef.current) {
            cancelTokenSourceRef.current.cancel("Operation canceled due to new request.");
            cancelTokenSourceRef.current = null;
        }

        setViewMode('result');
        setIsGenerating(true);
        setGeneratedText('');
        setIsSubmittingFeedback(false);
        setFeedbackSubmitted(null);
        onGenerationStart();

        if (generationMode === 'stream') {
            try {
                const streamUrl = route('ai.generate.description.stream');
                const csrfToken = (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content;

                if (!csrfToken) {
                    throw new Error('CSRF token not found. Please refresh the page.');
                }

                const response = await fetch(streamUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'text/event-stream, application/json, text/plain, */*',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        prompt: prompt,
                        locale: localeCode,
                    }),
                    credentials: 'include',
                });

                if (!response.ok) {
                    let errorData;
                    try { errorData = await response.json(); } catch (e) {}
                    const errorMsg = errorData?.message || `Failed to start stream: ${response.statusText} (Status: ${response.status})`;
                    throw new Error(errorMsg);
                }
                if (!response.body) throw new Error('ReadableStream not available.');

                const reader = response.body.pipeThrough(new TextDecoderStream()).getReader();
                let accumulatedResponse = "";
                let currentMessage = "";

                while (true) {
                    const { value, done } = await reader.read();
                    if (done) {
                        console.log('Stream finished.');
                        break;
                    }

                    accumulatedResponse += value;
                    let position;
                    while ((position = accumulatedResponse.indexOf('\n\n')) >= 0) {
                        currentMessage = accumulatedResponse.slice(0, position);
                        accumulatedResponse = accumulatedResponse.slice(position + 2);

                        if (currentMessage.startsWith('event: message')) {
                            const dataLine = currentMessage.substring(currentMessage.indexOf('data: ') + 6);
                            try {
                                const parsedData = JSON.parse(dataLine);
                                if (parsedData.text) {
                                    setGeneratedText((prev) => prev + parsedData.text);
                                }
                            } catch (e) { console.error('Failed to parse stream data JSON:', e, dataLine); }
                        } else if (currentMessage.startsWith('event: error')) {
                            const dataLine = currentMessage.substring(currentMessage.indexOf('data: ') + 6);
                            try {
                                const parsedError = JSON.parse(dataLine);
                                onError(`Stream error: ${parsedError.message || 'Unknown stream error'}`);
                            } catch (e) { onError(`Stream error: ${dataLine}`); }
                            console.error('Stream error event:', dataLine);
                            if (!reader.closed) reader.cancel();
                            break;
                        } else if (currentMessage.startsWith('event: end')) {
                            console.log('Stream end event received.');
                            if (!reader.closed) reader.cancel();
                            break;
                        }
                    }
                    if (!reader.closed && (currentMessage.startsWith('event: error') || currentMessage.startsWith('event: end'))) break;
                }
                onGenerationSuccess();

            } catch (error: any) {
                console.error("AI stream generation failed:", error);
                let errorMsg = 'Failed to generate content via stream.';
                if (error.message) errorMsg = error.message;
                setGeneratedText((prev) => prev + `\nError: ${errorMsg}`);
                onError(errorMsg);
            } finally {
                setIsGenerating(false);
            }
        } else {
            cancelTokenSourceRef.current = axios.CancelToken.source();
            try {
                const response = await axios.post(route('ai.generate.description'), {
                    prompt: prompt,
                    locale: localeCode,
                }, {
                    cancelToken: cancelTokenSourceRef.current.token
                });

                const description = response.data.description;
                if (description) {
                    setGeneratedText(description);
                    onGenerationSuccess();
                } else {
                    throw new Error("AI did not return content (JSON mode).");
                }
            } catch (error: any) {
                if (axios.isCancel(error)) {
                    console.log('Request canceled (JSON mode):', error.message);
                } else {
                    let errorMsg = 'Failed to generate content (JSON mode).';
                    if (axios.isAxiosError(error) && error.response?.data?.message) {
                        errorMsg = error.response.data.message;
                    } else if (error.message) {
                        errorMsg = error.message;
                    }
                    setGeneratedText(`Error: ${errorMsg}`);
                    onError(errorMsg);
                }
            } finally {
                setIsGenerating(false);
                cancelTokenSourceRef.current = null;
            }
        }
    };

    const handleInsert = () => {
        if (generatedText && !generatedText.startsWith('Error:')) {
            onInsert(generatedText);
            setIsDialogOpen(false);
        } else {
            onError("Cannot insert empty or error text.");
        }
    };

    const handleRegenerate = () => {
        if (!isGenerating) {
            handleGenerate();
        }
    };

    const handleEditPrompt = () => {
        setViewMode('prompt');
    };

    const handleFeedback = async (feedbackType: 'good' | 'bad') => {
        if (!generatedText || generatedText.startsWith('Error:') || isSubmittingFeedback || feedbackSubmitted) {
            return;
        }
        setIsSubmittingFeedback(true);
        try {
            await axios.post(route('ai.feedback.store'), {
                prompt: prompt,
                generated_text: generatedText,
                feedback: feedbackType,
                locale: localeCode,
            });
            setFeedbackSubmitted(feedbackType);
        } catch (error: any) {
            let errorMsg = 'Failed to submit feedback.';
            if (axios.isAxiosError(error) && error.response?.data?.message) {
                errorMsg = error.response.data.message;
            } else if (error.message) {
                errorMsg = error.message;
            }
            onError(errorMsg);
        } finally {
            setIsSubmittingFeedback(false);
        }
    };

    const handleOpenChange = (open: boolean) => {
        if (!open) {
            if (eventSourceRef.current) {
                eventSourceRef.current.close();
                eventSourceRef.current = null;
            }
            if (cancelTokenSourceRef.current) {
                cancelTokenSourceRef.current.cancel("Dialog closed by user.");
                cancelTokenSourceRef.current = null;
            }
            setViewMode('prompt');
            setIsGenerating(false);
            setIsSubmittingFeedback(false);
            setFeedbackSubmitted(null);
        }
        setIsDialogOpen(open);
    };

    const handleKeyDown = (event: React.KeyboardEvent<HTMLInputElement>) => {
        if (event.key === 'Enter' && prompt && !isGenerating) {
            event.preventDefault();
            handleGenerate();
        }
    };

    const feedbackDisabled = isGenerating || isSubmittingFeedback || !generatedText || generatedText.startsWith('Error:');

    useEffect(() => {
        return () => {
            if (eventSourceRef.current) {
                eventSourceRef.current.close();
            }
            if (cancelTokenSourceRef.current) {
                cancelTokenSourceRef.current.cancel("Component unmounted.");
            }
        };
    }, []);

    return (
        <Dialog open={isDialogOpen} onOpenChange={handleOpenChange}>
            <DialogTrigger asChild>
                <Button {...triggerButtonProps} onClick={openDialog}>
                    {triggerButtonContent}
                </Button>
            </DialogTrigger>
            <DialogContent className="sm:max-w-2xl">
                <DialogHeader>
                    <DialogTitle>{dialogTitle}</DialogTitle>
                    <DialogDescription>{dialogDescription} (Locale: {localeCode})</DialogDescription>
                </DialogHeader>

                {viewMode === 'prompt' && (
                    <div className="grid gap-4 py-0">
                        <div className="grid w-full items-center gap-1.5">
                            <Label htmlFor={`ai-prompt-${localeCode}`}>Prompt</Label>
                            <Input
                                id={`ai-prompt-${localeCode}`}
                                value={prompt}
                                onChange={(e) => setPrompt(e.target.value)}
                                onKeyDown={handleKeyDown}
                                placeholder="Enter your prompt..."
                            />
                        </div>
                        <div className="flex justify-end">
                            <Button
                                type="button"
                                size="sm"
                                onClick={handleGenerate}
                                disabled={!prompt}
                            >
                                <Sparkles className="mr-2 h-4 w-4" />
                                Generate
                            </Button>
                        </div>
                    </div>
                )}

                {viewMode === 'result' && (
                    <div className="grid gap-4 py-4">
                        <div className="grid gap-2">
                            <Label htmlFor={`ai-result-${localeCode}`}>Generated Text:</Label>
                            <Textarea
                                id={`ai-result-${localeCode}`}
                                value={isGenerating ? "Generating..." : generatedText}
                                readOnly
                                className="min-h-[150px] bg-muted/50"
                                placeholder="AI generation will appear here..."
                            />
                        </div>
                        <div className="flex justify-between items-center mt-2">
                            <div className="flex gap-1">
                                <Button
                                    variant={feedbackSubmitted === 'good' ? "secondary" : "ghost"}
                                    size="icon"
                                    onClick={() => handleFeedback('good')}
                                    disabled={feedbackDisabled || feedbackSubmitted === 'bad'}
                                    aria-label="Good generation"
                                    className="h-8 w-8"
                                >
                                    {isSubmittingFeedback && feedbackSubmitted !== 'good' ? <Loader2 className="h-4 w-4 animate-spin" /> :
                                        feedbackSubmitted === 'good' ? <Check className="h-4 w-4 text-green-600" /> :
                                            <ThumbsUp className="h-4 w-4" />}
                                </Button>
                                <Button
                                    variant={feedbackSubmitted === 'bad' ? "secondary" : "ghost"}
                                    size="icon"
                                    onClick={() => handleFeedback('bad')}
                                    disabled={feedbackDisabled || feedbackSubmitted === 'good'}
                                    aria-label="Bad generation"
                                    className="h-8 w-8"
                                >
                                    {isSubmittingFeedback && feedbackSubmitted !== 'bad' ? <Loader2 className="h-4 w-4 animate-spin" /> :
                                        feedbackSubmitted === 'bad' ? <Check className="h-4 w-4 text-red-600" /> :
                                            <ThumbsDown className="h-4 w-4" />}
                                </Button>
                            </div>
                            <div className="flex gap-2">
                                <Button
                                    type="button"
                                    variant="outline"
                                    size="icon"
                                    onClick={handleEditPrompt}
                                    disabled={isGenerating}
                                    aria-label="Edit Prompt"
                                >
                                    <Pencil className="h-4 w-4" />
                                </Button>
                                <Button
                                    type="button"
                                    variant="outline"
                                    size="icon"
                                    onClick={handleRegenerate}
                                    disabled={isGenerating}
                                    aria-label="Regenerate"
                                >
                                    {isGenerating ? <Loader2 className="h-4 w-4 animate-spin" /> : <RotateCcw className="h-4 w-4" />}
                                </Button>
                                <Button
                                    type="button"
                                    size="sm"
                                    onClick={handleInsert}
                                    disabled={
                                        isGenerating ||
                                        !generatedText ||
                                        generatedText.startsWith('Error:')
                                    }
                                >
                                    Insert
                                </Button>
                            </div>
                        </div>
                    </div>
                )}
                 <DialogFooter className="sm:justify-start mt-4 pt-4 border-t">
                 </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}

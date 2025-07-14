import * as React from "react"
import { AiGenerationDialog } from "@/components/Common/AiGenerationDialog"
import { ButtonProps } from "@/components/ui/button";
import { Sparkles } from "lucide-react";

import { cn } from "@/lib/utils"

export interface TextareaAiProps
  extends React.TextareaHTMLAttributes<HTMLTextAreaElement> {
  // Props for AiGenerationDialog
  aiLocaleCode: string;
  aiInitialPrompt?: string;
  aiDialogTitle?: string;
  aiDialogDescription?: string;
  aiTriggerButtonProps?: ButtonProps;
  aiTriggerButtonContent?: React.ReactNode;
  onAiInsert: (generatedText: string) => void;
  onAiError?: (errorMessage: string) => void;
  onAiGenerationStart?: () => void;
  onAiGenerationSuccess?: () => void;
  aiGenerationMode?: 'json' | 'stream';
  showAiButton?: boolean;
  // Props for character count
  aiMaxLength?: number;
  showAiCharCount?: boolean;
}

const TextareaAi = React.forwardRef<HTMLTextAreaElement, TextareaAiProps>(
  ({
    className,
    aiLocaleCode,
    aiInitialPrompt = "",
    aiDialogTitle,
    aiDialogDescription,
    aiTriggerButtonProps = { variant: "ghost", size: "icon", className: "h-7 w-7 text-muted-foreground hover:text-primary absolute top-1 right-1" },
    aiTriggerButtonContent = <Sparkles className="h-4 w-4" />,
    onAiInsert,
    onAiError,
    onAiGenerationStart,
    onAiGenerationSuccess,
    aiGenerationMode = "stream",
    showAiButton = true, // Default to true
    aiMaxLength,
    showAiCharCount = false,
    ...props
  }, ref) => {
    const currentLength = String(props.value || '').length;
    const isOverLimit = typeof aiMaxLength === 'number' && currentLength > aiMaxLength;

    return (
      <div className="relative w-full">
        <textarea
          className={cn(
            "flex min-h-[80px] w-full rounded-md border border-input dark:border-slate-800 bg-background text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 py-1 pl-2 pr-6",
            className,
            isOverLimit && showAiCharCount && typeof aiMaxLength === 'number'
              ? 'focus-visible:ring-destructive ring-2 ring-destructive ring-offset-2 !border-transparent dark:!border-transparent'
              : ''
          )}
          ref={ref}
          maxLength={aiMaxLength ? aiMaxLength + 100 : undefined}
          {...props}
        />
        {showAiButton && (
          <AiGenerationDialog
            localeCode={aiLocaleCode}
            initialPrompt={aiInitialPrompt || (props.value as string) || ""}
            dialogTitle={aiDialogTitle}
            dialogDescription={aiDialogDescription}
            triggerButtonProps={aiTriggerButtonProps}
            triggerButtonContent={aiTriggerButtonContent}
            onInsert={onAiInsert}
            onError={onAiError}
            onGenerationStart={onAiGenerationStart}
            onGenerationSuccess={onAiGenerationSuccess}
            generationMode={aiGenerationMode}
          />
        )}
        {showAiCharCount && typeof aiMaxLength === 'number' && (
          <div
            className={cn(
              "absolute bottom-1 right-2 text-xs px-1 py-0.5 rounded-md border bg-background", // Adjusted right padding to right-2
              "dark:border-slate-700",
              isOverLimit
                ? "text-destructive font-medium border-destructive dark:border-destructive"
                : "text-muted-foreground"
            )}
          >
            {currentLength}/{aiMaxLength}
          </div>
        )}
      </div>
    )
  }
)
TextareaAi.displayName = "TextareaAi"

export { TextareaAi }

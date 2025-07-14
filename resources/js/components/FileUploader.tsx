import React, { useEffect, useState } from 'react';
import { FilePond, registerPlugin } from 'react-filepond';
import { router } from '@inertiajs/react';

// Import FilePond styles
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

// Import and register FilePond plugins
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginImageCrop from 'filepond-plugin-image-crop';
import FilePondPluginImageResize from 'filepond-plugin-image-resize';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';

// Register the plugins
registerPlugin(
  FilePondPluginImagePreview,
  FilePondPluginImageCrop,
  FilePondPluginImageResize,
  FilePondPluginFileValidateType,
  FilePondPluginFileValidateSize
);

interface FileUploaderProps {
  onFileSelect: (file: File | null) => void;
  accept?: string[];
  maxSizeMB?: number;
  value?: File | null;
  existingFileUrl?: string; // New prop for existing file URL
  deleteUrl?: string; // New prop for delete URL
  label?: string;
  allowMultiple?: boolean;
  imagePreview?: boolean;
  imageCrop?: boolean;
  imageResize?: boolean;
  compact?: boolean; // New prop for compact mode
}

export default function FileUploader({
  onFileSelect,
  accept = ['image/*'],
  maxSizeMB = 1,
  value,
  existingFileUrl,
  deleteUrl,
  label = "Drop logo here or click to browse",
  allowMultiple = false,
  imagePreview = true,
  imageCrop = true,
  imageResize = true,
  compact = false
}: FileUploaderProps) {
  const [files, setFiles] = useState<any[]>([]);

  // Convert File to FilePond format when value changes
  useEffect(() => {
    if (value) {
      // New file selected
      setFiles([{
        source: value.name,
        options: {
          type: 'local',
          file: value
        }
      }]);
    } else if (existingFileUrl && !value) {
      // Load existing file from URL
      setFiles([existingFileUrl]);
    } else {
      setFiles([]);
    }
  }, [value, existingFileUrl]);

  const handleUpdateFiles = (fileItems: any[]) => {
    setFiles(fileItems);

    // Get the actual file from FilePond
    if (fileItems.length > 0) {
      const file = fileItems[0].file;
      onFileSelect(file);
    } else {
      onFileSelect(null);
    }
  };

  const handleDelete = () => {
    if (existingFileUrl && deleteUrl) {
      // Server deletion for existing files
      if (confirm('Are you sure you want to delete this file?')) {
        router.delete(deleteUrl);
      }
    } else {
      // Local deletion for newly uploaded files
      setFiles([]);
      onFileSelect(null);
    }
  };

  // Show delete button if there's either an existing file or a newly uploaded file
  const showDeleteButton = compact && (existingFileUrl || files.length > 0);

  return (
    <div className={`filepond-wrapper ${compact ? 'filepond-compact' : 'w-full'} relative`}>
      {showDeleteButton && (
        <button
          type="button"
          className="absolute top-1 right-1 z-20 px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs transition shadow-sm"
          onClick={handleDelete}
          title="Delete file"
        >
          ✕
        </button>
      )}
      <FilePond
        files={files}
        onupdatefiles={handleUpdateFiles}
        allowMultiple={allowMultiple}
        maxFiles={allowMultiple ? 10 : 1}
        maxFileSize={`${maxSizeMB}MB`}
        acceptedFileTypes={accept}
        credits={false}
        server={{
          load: (source, load, error, progress, abort, headers) => {
            // Handle loading existing files from URLs
            const request = new Request(source);
            fetch(request)
              .then(response => response.blob())
              .then(load)
              .catch(error);
            return {
              abort: () => {
                abort();
              }
            };
          }
        }}
        labelIdle={compact ? `<div class="filepond-compact-label">${label}</div>` : `${label} <span class="filepond--label-action">Browse</span>`}
        labelFileWaitingForSize="Calculating..."
        labelFileSizeNotAvailable="Size not available"
        labelFileLoading="Loading..."
        labelFileLoadError="Error"
        labelFileProcessing="Uploading..."
        labelFileProcessingComplete="Complete"
        labelFileProcessingAborted="Cancelled"
        labelFileProcessingError="Error"
        labelFileRemoveError="Error"
        labelTapToCancel="Cancel"
        labelTapToRetry="Retry"
        labelTapToUndo="Undo"
        labelButtonRemoveItem="Remove"
        labelButtonAbortItemLoad="Abort"
        labelButtonRetryItemLoad="Retry"
        labelButtonAbortItemProcessing="Cancel"
        labelButtonUndoItemProcessing="Undo"
        labelButtonRetryItemProcessing="Retry"
        labelButtonProcessItem="Upload"
        allowImagePreview={imagePreview}
        allowImageCrop={imageCrop}
        allowImageResize={imageResize}
        imageCropAspectRatio="1:1"
        imageResizeTargetWidth={200}
        imageResizeTargetHeight={200}
        stylePanelLayout={compact ? "compact" : "compact"}
        styleLoadIndicatorPosition="center bottom"
        styleProgressIndicatorPosition="center bottom"
        styleButtonRemoveItemPosition="left bottom"
        styleButtonProcessItemPosition="right bottom"
      />
    </div>
  );
}

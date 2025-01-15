import {BadgeX, File} from "lucide-react";
import {FileType} from "@/types/FileType.ts";


export default function FilePreview({file, handlerRemoveFile}: {
  file: FileType;
  handlerRemoveFile: () => void;
}) {
  return (
    <div className="mt-4 border rounded-md p-2 md:p-3 max-w-[16rem] relative">
      {file.type.includes('image') ? (
        <img
          src={file.url}
          alt="Preview"
          className="w-full object-contain rounded"
        />
      ) : (
        <>
          <div className="flex justify-center">
            <File size={100} strokeWidth={1}/>
          </div>
          <p className="mt-3 text-sm text-gray-500">{file.name}</p>
        </>
      )}
      <div className="absolute top-[-5px] right-[-10px]">
        <button type="button" onClick={handlerRemoveFile}>
          <BadgeX/>
        </button>
      </div>
    </div>
  );
}

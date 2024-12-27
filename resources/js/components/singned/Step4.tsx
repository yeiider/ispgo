import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Step4Props } from "@/interfaces/ISigned";
import { Loader, FileText } from 'lucide-react';

export default function Step4({ signature, url_signed }: Step4Props) {
  const [loading, setLoading] = useState(false);
  const [pdfUrl, setPdfUrl] = useState<string | null>(null);
  const [error, setError] = useState<string | null>(null);

  const handleSendSignature = async () => {
    setLoading(true);
    setError(null);
    setPdfUrl(null);

    try {
      const response = await fetch(signature);
      const blob = await response.blob();

      const formData = new FormData();
      formData.append("signature", blob, "signature.png");

      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "";

      const postResponse = await fetch(url_signed!, {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
        },
        body: formData,
      });

      if (!postResponse.ok) {
        throw new Error(`Error al enviar la firma: ${postResponse.statusText}`);
      }

      const data = await postResponse.json();
      setPdfUrl(data.pdf_url);
    } catch (error: any) {
      setError(`Error al enviar la firma: ${error.message}`);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="space-y-6">
      <h2 className="text-2xl font-semibold text-gray-800">Paso 4: Resumen y Confirmación</h2>
      <div className="bg-white shadow-md rounded-lg p-6">
        <h3 className="text-lg font-semibold mb-4 text-gray-700">Resumen de la Firma</h3>
        <p className="text-sm text-gray-600 mb-4">Has firmado exitosamente el documento. Aquí está tu firma:</p>
        <img src={signature} alt="Firma" className="border rounded-md mb-4" />
        <h3 className="text-lg font-semibold mb-4 text-gray-700">Documento Firmado</h3>
        {!pdfUrl && (
          <Button
            className="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm py-2 px-4 rounded flex items-center justify-center"
            onClick={handleSendSignature}
            disabled={loading}
          >
            {loading ? (
              <>
                <Loader className="animate-spin mr-2" size={16} />
                Enviando Firma...
              </>
            ) : (
              "Enviar Firma"
            )}
          </Button>
        )}
        {error && (
          <div className="mt-4">
            <p className="text-sm text-red-600">{error}</p>
          </div>
        )}
        {pdfUrl && (
          <div className="mt-4 space-y-2">
            <p className="text-sm text-green-600">Contrato firmado exitosamente.</p>
            <a
              href={pdfUrl}
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center text-blue-600 hover:text-blue-800"
            >
              <FileText className="mr-2" size={16} />
              Ver Contrato Firmado (PDF)
            </a>
          </div>
        )}
      </div>
    </div>
  );
}


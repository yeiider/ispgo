import { FileText, CheckCircle, Calendar } from 'lucide-react';
import { Card, CardHeader, CardTitle, CardDescription, CardContent, CardFooter } from "@/components/ui/card";
import { Button } from "@/components/ui/button";

interface SignedDocumentViewProps {
  pdfUrl: string;
  signedAt: string; // Asumimos que recibiremos la fecha de firma
}

export default function SignedDocumentView({ pdfUrl, signedAt }: SignedDocumentViewProps) {
  return (
    <div className="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
      <div className="mb-8">
        <div className="flex justify-between">
          <Card className="w-full max-w-2xl mx-auto">
            <CardHeader>
              <div className="flex items-center space-x-2">
                <CheckCircle className="text-green-500" size={24}/>
                <CardTitle className="text-2xl font-semibold text-gray-800">Documento Firmado</CardTitle>
              </div>
              <CardDescription>
                Este documento ya ha sido firmado anteriormente.
              </CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="bg-green-50 border border-green-200 rounded-lg p-4 flex items-start space-x-3">
                <div className="flex-shrink-0">
                  <CheckCircle className="text-green-500" size={20}/>
                </div>
                <div>
                  <p className="text-green-700 font-medium">Firma completada con éxito</p>
                  <p className="text-green-600 text-sm mt-1">
                    Su firma ha sido registrada y el documento está oficialmente firmado.
                  </p>
                </div>
              </div>
              <div className="flex items-center space-x-2 text-gray-600">
                <Calendar size={18}/>
                <span className="text-sm">Fecha de firma: {signedAt}</span>
              </div>
            </CardContent>
            <CardFooter className="flex justify-between items-center">
              <p className="text-sm text-gray-500">
                Puede descargar o ver el documento firmado en cualquier momento.
              </p>
              <Button asChild>
                <a
                  href={pdfUrl}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="flex items-center space-x-2"
                >
                  <FileText size={18}/>
                  <span>Ver PDF Firmado</span>
                </a>
              </Button>
            </CardFooter>
          </Card>
        </div>
      </div>
    </div>
          );
          }


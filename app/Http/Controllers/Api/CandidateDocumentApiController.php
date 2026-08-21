<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CandidateDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class CandidateDocumentApiController extends Controller
{
    #[OA\Get(
        path: "/candidate/documents",
        summary: "Daftar Dokumen Vault Pelamar",
        description: "Mengambil seluruh dokumen yang diunggah oleh kandidat.",
        security: [["bearerAuth" => []]],
        tags: ["Document Vault"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar dokumen",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Daftar dokumen berhasil diambil.",
                        "data" => [
                            [
                                "id" => 1,
                                "title" => "Sertifikat Laravel Expert",
                                "document_type" => "Sertifikat",
                                "file_path" => "candidate_vault/1/cert.pdf",
                                "file_size" => 1024500,
                                "created_at" => "2026-08-21T22:00:00.000000Z"
                            ]
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function index(Request $request)
    {
        $docs = CandidateDocument::where('user_id', $request->user()->id)->latest()->get();

        return $this->successResponse($docs, 'Daftar dokumen berhasil diambil.');
    }

    #[OA\Post(
        path: "/candidate/documents",
        summary: "Unggah Dokumen Baru ke Vault",
        description: "Mengunggah berkas dokumen pendukung baru milik pelamar.",
        security: [["bearerAuth" => []]],
        tags: ["Document Vault"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "title", type: "string", example: "Sertifikat AWS Solution Architect"),
                        new OA\Property(property: "document_type", type: "string", example: "Sertifikat"),
                        new OA\Property(property: "document_file", type: "string", format: "binary")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Dokumen berhasil diunggah",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Dokumen berhasil diunggah ke vault.",
                        "data" => [
                            "id" => 2,
                            "user_id" => 1,
                            "title" => "Sertifikat AWS Solution Architect",
                            "document_type" => "Sertifikat",
                            "file_path" => "candidate_vault/1/aws_cert.pdf",
                            "file_size" => 2048000
                        ],
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'document_type' => 'required|string|max:100',
            'document_file' => 'required|file|mimes:pdf,jpg,png,zip|max:10240',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi unggah dokumen gagal.', 422, $validator->errors());
        }

        $user = $request->user();
        $file = $request->file('document_file');
        $path = $file->store('candidate_vault/' . $user->id, 'public');

        $doc = CandidateDocument::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'document_type' => $request->document_type,
            'file_path' => $path,
            'file_size' => $file->getSize(),
        ]);

        return $this->successResponse($doc, 'Dokumen berhasil diunggah ke vault.', 201);
    }

    #[OA\Delete(
        path: "/candidate/documents/{id}",
        summary: "Hapus Dokumen Vault",
        description: "Menghapus berkas dokumen milik kandidat.",
        security: [["bearerAuth" => []]],
        tags: ["Document Vault"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 1))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Dokumen berhasil dihapus",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Dokumen berhasil dihapus.",
                        "data" => null,
                        "errors" => null
                    ]
                )
            )
        ]
    )]
    public function destroy(Request $request, $id)
    {
        $doc = CandidateDocument::find($id);

        if (!$doc) {
            return $this->errorResponse('Dokumen tidak ditemukan.', 404);
        }

        if ($doc->user_id !== $request->user()->id) {
            return $this->errorResponse('Anda tidak memiliki akses menghapus dokumen ini.', 403);
        }

        if (Storage::disk('public')->exists($doc->file_path)) {
            Storage::disk('public')->delete($doc->file_path);
        }

        $doc->delete();

        return $this->successResponse(null, 'Dokumen berhasil dihapus.');
    }
}

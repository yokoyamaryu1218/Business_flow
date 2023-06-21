<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DocumentService
{
    // 手順の順番を取得する関数
    public function numbering($number)
    {
        // ドキュメントナンバーの各部分を分割
        $parts = explode('-', $number);
        $letterPart = $parts[0]; // ドキュメントナンバーのアルファベット部分
        $numberPart = intval($parts[1]); // ドキュメントナンバーの数字部分
        $subNumberPart = intval($parts[2]); // ドキュメントナンバーのサブナンバー部分

        if ($subNumberPart < 9) {
            // サブナンバーが9未満の場合、次のサブナンバーを生成
            $subNumberPart++;
        } else {
            // サブナンバーが9以上の場合、次のナンバーとサブナンバーを生成
            $numberPart += 100;
            $subNumberPart = 1;
        }

        // 新しいドキュメントナンバーを作成
        $newDocumentNumber = sprintf('%s-%d-%d', $letterPart, $numberPart, $subNumberPart);

        return $newDocumentNumber;
    }

    public function saveText($number, $details)
    {
        // ファイルの保存先パスを生成
        $filePath = 'documents/' . $number . '.txt';
        // ファイルに内容を保存
        Storage::put($filePath, $details);
    }

    public function getContents($fileName)
    {
        $filePath = 'documents/' . $fileName;

        // テキストファイルの内容を取得する
        $fileContents = Storage::disk('local')->get($filePath);

        return $fileContents;
    }

    public function downloadFile($fileName)
    {
        $filePath = 'documents/' . $fileName;

        $fileContents = Storage::get($filePath);

        $headers = [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        return new Response($fileContents, 200, $headers);
    }
}

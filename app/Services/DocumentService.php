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
        if ($number) {
            // ドキュメントナンバーの各部分を分割
            $parts = explode('-', $number);
            $letterPart = $parts[0]; // ドキュメントナンバーのアルファベット部分
            $numberPart = intval($parts[1]); // ドキュメントナンバーの数字部分
            $subNumberPart = intval($parts[2]); // ドキュメントナンバーのサブナンバー部分

            if ($subNumberPart < 9) {
                // サブナンバーが9未満の場合、次のサブナンバーを生成
                $subNumberPart++;
            } else {
                // サブナンバーが9以上の場合
                if ($numberPart === 900 && $subNumberPart === 9) {
                    // 数字部分が900でサブナンバーが9の場合
                    $letterPart++;
                    $numberPart = 100;
                    $subNumberPart = 1; // サブナンバーを1にリセット
                } else {
                    if ($numberPart === 900) {
                        // 数字部分が900の場合、アルファベット部分を次の文字に変更し、数字部分を100にリセット
                        $letterPart++;
                        $numberPart = 100;
                        $subNumberPart = 1; // サブナンバーを1にリセット
                    } else {
                        // 数字部分を+100し、サブナンバーを1にリセット
                        $numberPart += 100;
                        $subNumberPart = 1; // サブナンバーを1にリセット
                    }
                }
            }

            // 新しいドキュメントナンバーを作成
            $newDocumentNumber = sprintf('%s-%d-%d', $letterPart, $numberPart, $subNumberPart);
        } else {
            $newDocumentNumber = "A-100-1";
        }
        return $newDocumentNumber;
    }

    public function saveText($number, $details)
    {
        // ファイルの保存先パスを生成
        $filePath = 'documents/' . $number . '.txt';
        // ファイルに内容を保存
        file_put_contents(public_path($filePath), $details);
    }

    public function getContents($fileName)
    {
        $filePath = 'documents/' . $fileName;

        $fileContents = file_get_contents(public_path($filePath));

        return $fileContents;
    }

    public function downloadFile($fileName)
    {
        $filePath = 'documents/' . $fileName;

        $headers = [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        return response()->download(public_path($filePath), $fileName, $headers);
    }
}

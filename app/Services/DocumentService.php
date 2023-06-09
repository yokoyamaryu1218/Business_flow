<?php

namespace App\Services;

use App\Models\Document;

class DocumentService
{
    // 手順の順番を取得する関数
    public function numbering($number)
    {
        // ドキュメントナンバーの各部分を分割
        $parts = explode('-', $number);
        $letterPart = $parts[0];
        $numberPart = intval($parts[1]);
        $subNumberPart = intval($parts[2]);
        
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
}

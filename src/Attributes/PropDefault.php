<?php

namespace Takemo101\SimpleDTO\Attributes;

use Attribute;

/**
 * 配列からオブジェクトに変換する場合の
 * プロパティのデフォルト値を設定できる
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final class PropDefault
{
    /**
     * constructor
     *
     * @param mixed $value
     */
    public function __construct(
        public readonly mixed $value,
    ) {
        //
    }
}

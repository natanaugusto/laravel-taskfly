<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    const DUE_DATETIME_FORMAT = 'Y-m-d H:i:s';
    const NAMESPACE_UUID = 'c8bc2dc4-0495-11ed-b939-0242ac120002';
    const SHORTCODE_LENGTH = 8;

    protected $fillable = ['creator_id', 'title', 'due'];

    public function save(array $options = []): bool
    {
        if (!$this->exists) {
            if (empty($this->shortcode)) {
                $this->shortcode = self::shortcodefy(string: $this->title);
            }
            if (empty($this->uuid)) {
                $this->uuid = Uuid::uuid5(ns: self::NAMESPACE_UUID, name: $this->title);
            }
        }
        return parent::save($options);
    }

    protected static function shortcodefy(string $string): string
    {
        $string = preg_replace(pattern: '/[^\da-z]/i', replacement: '', subject: $string);
        $short = $string[0];
        $strLen = strlen(string: $string);
        $m = intval(value: ceil(num: $strLen / (self::SHORTCODE_LENGTH / 2)));
        while (strlen(string: $short) < self::SHORTCODE_LENGTH / 2 - 1) {
            if ($m < $strLen && !empty($string[$m])) {
                $short .= $string[$m];
                $m += $m;
            } else {
                $m = $m - $strLen;
                $short .= $string[$m];
            }
        }
        $short .= $string[$strLen - 1];
        $short = strtoupper(string: $short);
        $code = str_pad(
            string: self::where('shortcode', 'like', "#{$short}-%")->count() + 1,
            length: 4,
            pad_string: '0',
            pad_type: STR_PAD_LEFT
        );
        return "#{$short}-{$code}";
    }
}

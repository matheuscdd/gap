<?php

namespace App\Constraints;

class ClientKeysConstraints {
    public const NAME = 'name';
    public const CNPJ = 'CNPJ';
    public const CEP = 'CEP';
    public const ADDRESS = 'address';
    public const CELLPHONE = 'cellphone';
    public const CREATED_BY = 'created_by';
    public const UPDATED_BY = 'updated_by';
    public const TABLE = 'clients';
    public const ALL = [self::NAME, self::CNPJ, self::CEP, self::ADDRESS, self::CELLPHONE];
}

<?php

namespace App\Constraints;

class BudgetTransConstraints {
    public const CLIENT = 'cliente';
    public const DELIVERY_DATE = 'data de entrega';
    public const DELIVERY_ADDRESS = 'endereço de entrega';
    public const PROVIDER_NAME = 'nome do fornecedor';
    public const PROVIDER_CITY = 'cidade do fornecedor';
    public const UNLOADED = 'descarga';
    public const PAYMENT_STATUS = 'status do pagamento';
    public const PAYMENT_METHOD = 'método de pagamento';
    public const PAYMENT_DATE = 'data do pagamento';
    public const REVENUE = 'faturamento';
    public const COST = 'custo';
    public const CREATED_BY = 'criado por';
    public const UPDATED_BY = 'atualizado por';
}


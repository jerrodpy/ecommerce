<?php

namespace App\Repositories\Base;

use Exception;

interface PaginableContract
{
    public const FIELD_TOTAL = 'total';

    public const FIELD_LAST_PAGE = 'last_page';

    public const FIELD_PER_PAGE = 'per_page';

    public const FIELD_CURRENT_PAGE = 'current_page';

    public const FIELD_SORT_ORDER = 'sort_order';

    public const FIELD_FILTERS = 'filters';

    public const FIELD_ITEMS = 'items';

    public const FIELD_SORT_ORDER_COLUMN = 'column';

    public const FIELD_SORT_ORDER_ORDER = 'order';

    public const FIELD_NAME = 'name';

    public const SORT_DESK = 'desc';

    public const SORT_ASC = 'asc';

    public const DEFAULT_PAGE = 1;

    public const DEFAULT_PER_PAGE = 20;

    public const REQUEST_RULES = [
        self::FIELD_FILTERS => [
            'nullable',
            'array',
        ],
        self::FIELD_SORT_ORDER => [
            'array',
        ],
        self::FIELD_SORT_ORDER . '.*.' . self::FIELD_SORT_ORDER_COLUMN => [
            'string',
        ],
        self::FIELD_SORT_ORDER . '.*.' . self::FIELD_SORT_ORDER_ORDER => [
            'string',
        ],
        self::FIELD_PER_PAGE => [
            'nullable',
            'integer',
            'min:1',
        ],
        self::FIELD_CURRENT_PAGE => [
            'nullable',
            'integer',
            'min:1',
        ],
    ];

    /**
     *
     * @throws Exception
     */
    public function paginate(array $filter = []): array;
}

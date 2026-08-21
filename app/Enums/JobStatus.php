<?php

namespace App\Enums;

enum JobStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case CLOSED = 'closed';
    case DRAFT = 'draft';
}

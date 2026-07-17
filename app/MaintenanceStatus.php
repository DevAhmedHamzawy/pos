<?php

namespace App;

enum MaintenanceStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Delivered = 'delivered';
}

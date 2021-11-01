<?php

/*
 * This file is part of the AdminLTE bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Utils;

interface Constants
{
    public const COLOR_AQUA = 'aqua';
    public const COLOR_GREEN = 'green';
    public const COLOR_RED = 'red';
    public const COLOR_YELLOW = 'yellow';
    public const COLOR_GREY = 'grey';
    public const COLOR_BLACK = 'black';
    public const SOFTWARE='CREATIV-MANAGER';
    public const VERSION='1.0.0';
    public const STATUS='success';
    const PENDING="Pending";
    const ACCEPTED="Accepted";
    const TRANSFORM="Transform";
    const REJECTED="Rejected";
    const EXPIRED="Expired";
    const PAID="paid";
    const ADVANCED="Advanced";
    const ISFACTURE="isfacture";
    const ISBONACHAT="isbonachat";
    const ISRECEPTION="isreception";
    const MAGASIN="Magasin";
    const BOUTIQUE="Boutique";
    const CHAMBRE="Chambre";
    const STUDIO="Studio";
    const APPARTEMENT="appartement";
    const DISPONIBLE="disponible";
    const ENTRAVAUX="travaux";
    const OCCUPE="occupe";

    /**
     * Used in:
     * - Model\NotificationModel
     * - Twig\AdminExtension
     */
    public const TYPE_SUCCESS = 'success';
    public const TYPE_WARNING = 'warning';
    public const TYPE_ERROR = 'error';
    public const TYPE_INFO = 'info';
}

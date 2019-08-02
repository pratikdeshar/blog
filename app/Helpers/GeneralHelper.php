<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

function findPageTitle()
{
    $subTitle = '';
    if (request()->segment(3)) {
        if ($title = request()->segment(4)) {
            $subTitle = $title;
        } else {
            $subTitle = request()->segment(3);
        }

    } else {
        $subTitle = 'List';
    }
    $title = sprintf("
<h1 class=\"page-title\">%s
    <small>%s</small>
</h1>", ucfirst(request()->segment(2)), $subTitle);

    echo $title;
}

function getFormattedUri()
{
    $uris     = array_filter(explode('/', request()->getRequestUri()));
    $all      = '';
    $count    = count($uris);
    $sequence = '';
    foreach ($uris as $key => $t) {
        $sequence .= "/$t";
        if ($t != 'admin' && $t) {
            if ($count != $key) {
                $m   = '<i class="fa fa-circle"></i>';
                $all .= sprintf("<li><a href=\"%s\">%s</a>%s</li>", $sequence, $t, $m);
            } else {
                $all .= "<li><span>$t</span></li>";
            }
        }
    }

    echo sprintf("<div class=\"page-bar\">
    <ul class=\"page-breadcrumb\">
        <li>
            <a href=\"/admin/dashboard\">Home</a>
            <i class=\"fa fa-circle\"></i>
        </li>
        %s
    </ul>
</div>", $all);
}

/**
 * @param  null  $message
 */
function flashUpdated($message = null)
{
    flash()->success($message ?? "Updated Successfully.");
}

/**
 * @param  null  $message
 */
function flashCreated($message = null)
{
    flash()->success($message ?? "Created Successfully.");
}

/**
 * @param  null  $message
 */
function flashDuplicate($message = null)
{
    flash()->warning($message ?? "Duplicate Entry Found.");
}

/**
 * @param  null  $message
 */
function flashDeleted($message = null)
{
    flash()->success($message ?? "Delete Successfully");
}

/**
 * store image on disk
 *
 * @param      $file
 * @param      $path
 * @param  null  $fileName
 * @return false|string
 */
function store($file, $path, $fileName = null)
{
    $file     = request()->file($file);
    $fileName = time().str_random(5).'.'.$file->getClientOriginalExtension();
    $filePath = public_path('storage/'.$path);
    $thumbPath = public_path('storage/'.$path.'/thumbnail');

    if (!file_exists($filePath)) {
        mkdir($filePath, 0777, true);
    }
    if (!file_exists($thumbPath)) {
        mkdir($thumbPath, 0777, true);
    }
    $storeFileName    = $path.'/'.$fileName;
    $fullFileNamePath = public_path('storage/'.$storeFileName);

    $image = Image::make($file)->orientate();
    $image->save($fullFileNamePath);

    $thumbnailPath = public_path('storage/'.$path.'/thumbnail/'.$fileName);

    $img = $image->resize(300, null, function ($constraint) {
        $constraint->aspectRatio();
    });

    $img->save($thumbnailPath);

    return $storeFileName;
}


/**
 * Delete image
 * @param $fileName
 */
function unlinkImage($fileName)
{
    if ($fileName || !(Storage::exists($fileName))) {
        $path  = explode('/', $fileName);
        $new   = ['thumbnail'];
        $count = count($path);
        array_splice($path, $count - 1, 0, $new);

        Storage::delete($fileName);
        if (Storage::exists(implode('/', $path))) {
            Storage::delete(implode('/', $path));
        }
    }
}

/**
 * Status change
 * @param $status
 * @param  string  $whenTrue
 * @param  string  $whenFalse
 * @return string
 */
function getToggleStatus($status, $whenTrue = 'Active', $whenFalse = 'Inactive')
{
    if ($status) {
        return '<span class="m-badge m-badge--success m-badge--wide">'.$whenTrue.'</span>';
    } else {
        return '<span class="m-badge m-badge--danger m-badge--wide">'.$whenFalse.'</span>';
    }
}

/**
 * @param      $file
 * @param  bool  $thumbnail
 * @return string
 */
function getFile($file, $thumbnail = false, $type = "avatar")
{

    if (!$file || !(Storage::exists($file))) {
        if ($type == 'item') {
            return asset('assets/images/item.svg');

        } elseif (($type == 'venue') && $thumbnail) {
            return asset('assets/images/venue.svg');

        } elseif ($type == 'venue') {
            return asset('assets/images/venue.svg');

        } elseif ($type == 'logo') {
            return asset('logo-small.png');

        }

        return asset('assets/images/venue.svg');
    }
    if ($thumbnail) {
        $path  = explode('/', $file);
        $new   = ['thumbnail'];
        $count = count($path);
        array_splice($path, $count - 1, 0, $new);

        return Storage::url(implode('/', $path));
    }

    return Storage::url($file);
}

/**
 * Get payment methods
 *
 * @return array
 */
function getPaymentMethods()
{
    return [
        \App\Constants\PaymentMethod::EFT,
        \App\Constants\PaymentMethod::ACCOUNT,
        \App\Constants\PaymentMethod::CREDIT_CARD,
        \App\Constants\PaymentMethod::PAYPAL,
    ];
}

/**
 * @return array
 */
function getOrderAcceptTypes()
{
    return [
        \App\Constants\OrderAcceptType::DAY_BEFORE,
        \App\Constants\OrderAcceptType::DAY_OFF,
        \App\Constants\OrderAcceptType::ANYTIME,
    ];
}

/**
 * Get setting by slug
 *
 * @param  null  $slug
 * @return mixed
 */
function getSetting($slug = null)
{

    return '';
}

/**
 * @param  string  $type
 * @return mixed
 */
function menus($type = 'admin')
{
    if ($type == 'admin') {
        return app(\App\Services\MenuService::class)->groupsWithMenus($type);
    }

    return app(\App\Services\MenuService::class)->menus($type);

}

/**
 * Returns Url
 *
 * @param  string  $type
 * @param  integer  $id
 * @return mixed
 */
function getOrderUrl($type, $id)
{
    if ($type == 'next') {
        $order = \App\Entities\Order::where('id', '>', $id)->orderBy('id', 'asc')->first();
    } else {
        $order = \App\Entities\Order::where('id', '<', $id)->orderBy('id', 'desc')->first();
    }
    if ($order) {
        return route('order-detail.detail', $order->id);
    } else {
        return null;
    }
}

/**
 * Returns Url
 *
 * @param  string  $type
 * @param  integer  $id
 * @return mixed
 */
function getSupplierOrderUrl($type, $id, $route = 'view', $total_count = false)
{
    if ($type == 'next') {
        $order = \App\Entities\Order::where('id', '>',
            $id)->where(loggedInVenue('type') == \App\Constants\RoleConstant::RETAILER ? 'retailer_id' : 'supplier_id',
            loggedInVenue('id'))->whereBetween('delivery_date',
            [session()->get('order_from_date'), session()->get('order_to_date')])->orderBy('id',
            'asc')->first();
    } else {
        $order = \App\Entities\Order::where('id', '<', $id)->orderBy('id',
            'desc')->where(loggedInVenue('type') == \App\Constants\RoleConstant::RETAILER ? 'retailer_id' : 'supplier_id',
            loggedInVenue('id'))->whereBetween('delivery_date',
            [session()->get('order_from_date'), session()->get('order_to_date')])->first();
    }
    if ($order) {
        $orderPage = loggedInVenue('type') == \App\Constants\RoleConstant::RETAILER ? 'orders' : 'order';
        if ($route == 'edit') {
            return route('front.'.loggedInVenue('type').'.'.$orderPage.'.edit', $order->id);
        } else {
            return route('front.'.loggedInVenue('type').'.'.$orderPage.'.show',
                !empty($total_count) ? [$order->id, 'total_count='.$total_count] : $order->id);
        }

    } else {
        return null;
    }
}

function getOrderEditUrl($type, $id)
{

    if ($type == 'next') {
        $order = \App\Entities\Order::where('id', '>', $id)->orderBy('id', 'asc')->first();
    } else {
        $order = \App\Entities\Order::where('id', '<', $id)->orderBy('id', 'desc')->first();
    }
    if ($order) {
        return route('order-detail.edit', $order->id, 'edit');
    } else {
        return null;
    }
}

/**
 * Returns Order Types
 *
 * @return array
 */
function getOrderStatusType()
{

    return [
        getStatusIdByName(\App\Constants\StatusConstant::PENDING)  => title_case(\App\Constants\StatusConstant::PENDING),
        getStatusIdByName(\App\Constants\StatusConstant::ACCEPTED) => title_case(\App\Constants\StatusConstant::ACCEPTED),
        getStatusIdByName(\App\Constants\StatusConstant::DECLINED) => title_case(\App\Constants\StatusConstant::DECLINED),
    ];
}

function getStatusIdByName($value)
{
    $status = \App\Entities\Status::where('status', $value)->firstOrFail();

    return $status->id;
}

/**
 * @return array
 */
function getCommunicationFilters()
{
    return \App\Constants\CommunicationFilterConstant::OPTIONS;


}

/**
 * @param  key
 * @return object || string
 */
function loggedInVenue($key = '')
{
    $venue = session()->get('venue');

    if ($key) {
        return $venue->{$key} ?? '';
    }

    return $venue;
}

function getImageValidation($size = 2048, $required = false)
{
    if ($required) {
        return 'max:'.$size.'|mimes:jpeg,jpg,png,svg|required';
    } else {
        return 'max:'.$size.'|mimes:jpeg,jpg,png';
    }
}

function getPriceOptionOfVenue($venueId)
{
    $priceOption = \App\Entities\VenueDetail::where('venue_id', $venueId)->first();

    return $priceOption->price_to_all_item;

}


/**
 * Returns Order Types
 *
 * @return array
 */
function getCustomerFilters()
{
    class CustomerFilterConstant
    {
        const PERFORMING       = 'Performing';
        const UNDER_PERFORMING = 'Under Performing';
        const RECENT_ORDER     = 'New / Recent Order place';
        const NO_ORDER         = 'no order placed';
    }

    return [
        1 => 'Performing',
        2 => 'Under Performing',
        3 => 'New / Recent ORder place',
        4 => 'no order placed',
    ];
}

/**
 * Calculates Growth Percentage
 *
 * @param  int  $current
 * @param  int  $previous
 * @return int
 */
function calculateGrowth($current, $previous)
{
    if ($previous <= 0 && $current == 0) {
        return '0';
    } else {
        if ($previous == 0 && $current > 0) {
            return '+100';
        } else {
            $percentage = (($current - $previous) * 100) / $previous;
            $percentage = round($percentage, 2);

            return $percentage > 0 ? '+'.$percentage : $percentage;
        }
    }
}


/**
 * Returns Verbal date
 *
 * @param  int  $datetime
 * @return string
 */
function getVerbalDate($datetime)
{
    if (empty($datetime)) {
        return '';
    }
    $dt = \Carbon\Carbon::parse($datetime);
    if ($dt->isTomorrow()) {
        return 'Tomorrow';
    }
    if ($dt->isToday()) {
        return 'Today';
    }

    return $dt->format(' l jS M Y');
}

/**
 * Get money format
 *
 * @param $money
 * @return float
 */
function moneyFormat($money)
{
    return round($money, 2);
    // money_format('%i', $money);
}

/**
 * Check connected supplier
 * @param $supplierId
 * @return
 */
function checkAssociation($supplierId)
{
    $retailerId = loggedInVenue()->id ?? false;
    if (!$retailerId) {
        return false;
    }
    $connected = app(\App\Services\VenueAssociationService::class)->model()->where('retailer_id',
        $retailerId)->where('supplier_id',
        $supplierId)->first();

    return $connected;
}

/**
 * Get first Items Category Image
 * @param      $id
 * @param  bool  $thumbnail
 * @return string
 */
function getCategoryImage($id, $thumbnail = false)
{
    $item = app(\App\Services\ItemService::class)->model()->where('item_category_id', $id)->where('image', '!=', null)
        ->orderBy('name',
            'ASC')
        ->first();

    return getFile($item->image ?? '', $thumbnail, 'item');
}


//get actual price
function getActualPrice($price)
{
    return moneyFormat($price / (1 + getSetting('tax') / 100));
}

///get sales price
function getSalesPrice($price)
{
    return moneyFormat($price * (1 + ($price * getSetting('tax') / 100)));
}

/**
 * setVenue
 * @param $venue
 * @return
 */
function setVenue($venue)
{
    $isSet = loggedInVenue() ?? false;
    if (!$isSet) {
        session()->put('venue', $venue);
    }
}

/**
 * Get the back url
 *
 * @param $route
 * @return string
 */
function getBackUrl($route)
{
    if (request()->has('back_to')) {
        return request()->get('back_to').'?back=true';
    } elseif ($route) {
        return $route;
    } else {
        return url()->previous();
    }
}

/**
 * get connection status id of retailer and supplier
 */
function getVenueConnectionStatusId($supplierId, $retailerId)
{
    $connection = app(\App\Services\VenueAssociationService::class)->model()->where('retailer_id', $retailerId)
        ->where('supplier_id', $supplierId)->first();
    if ($connection) {
        return $connection->status_id;
    } else {
        return request()->segment(1) != 'admin' ? getStatusIdByName(\App\Constants\StatusConstant::PENDING)
            : getStatusIdByName(\App\Constants\StatusConstant::ACCEPTED);
    }

}

/**
 * show hide sidebar
 */
function hasSidebar($childMenus)
{
    if (isset($childMenus)) {
        if (empty($childMenus)) {
            return 'display-none-important';
        }
    }
}

/*
    * function for get price with decimal value
    * @param $id
    * @return bool
*/
function getFormattedPrice($price)
{
    return '$'.number_format($price, 2, '.', ',');
}

/**
 * Default available variables
 *
 * @return array
 */
function getEmailVariables($extra)
{
    $variables = [
        'FIRST_NAME',
        'LAST_NAME',
    ];

    return array_unique(array_merge($variables, $extra));
}


function logoUrl()
{


    return '';
}

/**
 *get formatted date
 * yyyy-mm-dd from dd-mm-yyyy
 */
function getFormattedDate($date)
{
    return date('Y-m-d', strtotime($date));
}

/**
 *get formatted date
 * dd-mm-yyyy  from yyyy-mm-dd
 */
function getFormattedDateToDMY($date)
{
    return date('d-m-Y', strtotime($date));
}



/**
 * Current timezone convert as per requested timezone
 * @param $timezone
 * @param  null  $dateString
 * @return \Carbon\Carbon
 */
function timezoneConvert($timezone, $dateString = null)
{

    if ($dateString) {
        return \Carbon\Carbon::parse($dateString)->setTimezone($timezone);

    }

    return \Carbon\Carbon::now($timezone);
}

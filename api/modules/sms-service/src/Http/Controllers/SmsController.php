<?php
namespace Tamani\Sms\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Tamani\Sms\Http\Resources\PhoneBookResource;
use Tamani\Sms\Http\Resources\SmsResource;
use Tamani\Sms\Models\PhoneBook;
use Tamani\Sms\Models\SmsMessage;

class SmsController extends Controller
{
    public function phonebook()
    {
        $qb = QueryBuilder::for(PhoneBook::class)->paginate();

        return PhoneBookResource::collection($qb);
    }

    public function showByPbId(string $phonebookId)
    {
        $qb = QueryBuilder::for(SmsMessage::class)->where('phonebook_id', $phonebookId)->paginate();

        return SmsResource::collection($qb);
    }
}

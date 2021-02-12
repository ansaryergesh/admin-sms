<?php

use Illuminate\Database\Seeder;
use App\SmsStatus;
class SmsStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SmsStatus::create([
            'status' => '100',
            'name' => 'В очереди',
            'description'=>'Сообщение поставлено в очередь на отправку. Доставка сообщения ещѐ не начата.'
        ]);
        SmsStatus::create([
            'status' => '101',
            'name' => 'Отправлено',
            'description'=>'Сообщение успешно отправлено на оборудование оператора.'
        ]);
        SmsStatus::create([
            'status' => '102',
            'name' => 'Доставлено',
            'description'=>'Сообщение успешно доставлено абоненту .'
        ]);
        SmsStatus::create([
            'status' => '103',
            'name' => 'Истёк срок',
            'description'=>'Истёк срок жизни сообщения в процессе доставки на абонентское оборудование .'
        ]);
        SmsStatus::create([
            'status' => '104',
            'name' => 'Удалено',
            'description'=>'Сообщение удалено из очереди на доставку на стороне SMS оператора .'
        ]);
        SmsStatus::create([
            'status' => '105',
            'name' => 'Невозможно доставить',
            'description'=>'Сообщение невозможно доставить'
        ]);
        SmsStatus::create([
            'status' => '106',
            'name' => 'Прочитано',
            'description'=>'Сообщение прочитано абонентом'
        ]);
        SmsStatus::create([
            'status' => '107',
            'name' => 'Неопределенный статус',
            'description'=>'Сообщение находится в неопределѐнном статусе'
        ]);
        SmsStatus::create([
            'status' => '108',
            'name' => 'Отвергнуто системой оператора',
            'description'=>'Сообщение отвергнуто системой оператора'
        ]);
        SmsStatus::create([
            'status' => '109',
            'name' => 'Отвергнуто системой SMS-Consult',
            'description'=>'Сообщение отвергнуто системой SMS-Consult'
        ]);
        SmsStatus::create([
            'status' => '200',
            'name' => 'Общая ошибка',
            'description'=>'Общая ошибка, покрывающая все неспецифичные причины синтаксической некорректности исходного запроса'
        ]);
        SmsStatus::create([
            'status' => '201',
            'name' => 'Некорректный формат',
            'description'=>'Некорректный формат идентификатора сообщения'
        ]);
        SmsStatus::create([
            'status' => '202',
            'name' => 'Некорректный формат',
            'description'=>'Некорректный формат имени отправителя'
        ]);
        SmsStatus::create([
            'status' => '203',
            'name' => 'Некорректный формат',
            'description'=>'Некорректный формат получателя'
        ]);
        SmsStatus::create([
            'status' => '204',
            'name' => 'Некорректный формат',
            'description'=>'Некорректная длина тела запроса'
        ]);

        SmsStatus::create([
            'status' => '205',
            'name' => 'Заблокирован пользователь',
            'description'=>'Пользователь заблокирован в системе SMS-Consult'
        ]);
        SmsStatus::create([
            'status' => '206',
            'name' => 'Заблокирован пользователь',
            'description'=>'Пользователь заблокирован в системе SMS-Consult по причине задолженности (чаще всего для пост-оплатных клиентов);'
        ]);
        SmsStatus::create([
            'status' => '207',
            'name' => 'Заблокирован пользователь',
            'description'=>'Пользователь заблокирован в системе SMS-Consult по причине превышения лимита (чаще всего для пред-оплатных клиентов)'
        ]);

    }
}

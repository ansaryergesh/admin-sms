<?php

use Illuminate\Database\Seeder;
use App\SmsType;
use Carbon\Carbon;
class SmsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SmsType::create([
            'name' => 'Идентификация',
            'description' => 'Код потверждения',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Подпись ',
            'description' => 'Подпись',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Отказ клиенту',
            'description' => 'Отказ клиенту при регистрации',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Отказ клиента',
            'description' => 'Клиент сам отказался при одобрении',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Льготный период-1',
            'description' => '1 день осталось до окончании Льготного периода',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Льготный период',
            'description' => 'Последний день Льготного периода',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Стандартный-3',
            'description' => '3 дня осталось до окончании Стандартного периода',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Стандартный-2',
            'description' => '2 дня осталось до окончании Стандартного периода',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Стандартный-1',
            'description' => '1 день осталось до окончании Стандартного периода',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Стандартный',
            'description' => 'Последний день Стандартного периода',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Софт-1',
            'description' => 'Просрочка Софт 1 день',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Софт-2',
            'description' => 'Просрочка Софт 2 дня',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Софт-3',
            'description' => 'Просрочка Софт 3 дня',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Софт-4',
            'description' => 'Просрочка Софт 4 дня',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Софт-5',
            'description' => 'Просрочка Софт 5 дня',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Софт-6',
            'description' => 'Просрочка Софт 6 дня',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Софт-7',
            'description' => 'Просрочка Софт 7 дня',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Хард',
            'description' => 'Просрочка Хард',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Дата обещание',
            'description' => 'Дата обещание',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Продление подпись',
            'description' => 'Продление подпись',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Заим продлен успешно',
            'description' => 'Ваш заим продлен',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Погашен',
            'description' => 'Заим Погашен',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Рекламный',
            'description' => 'Рекламная рассылка смс',
            'sms_text'=>''
        ]);
        SmsType::create([
            'name' => 'Реструктуризация',
            'description' => 'Реструктуризация',
            'sms_text'=>''
        ]);
    }
}

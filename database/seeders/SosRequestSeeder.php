<?php

namespace Database\Seeders;

use App\Models\SOSRequest;
use Database\Factories\SosFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SosRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SOSRequest::create([
            'problem'=>'اتمسكت فى لجنة بدون رخص ',
            'phone'=>'01000000000',
            'address'=>'القاهرة',
            'lat'=>30.05,
            'long'=>31.05,
            'status'=>'new',
            'lawyer_id'=>1,
            'client_id'=>1
        ]);
        SOSRequest::create([
            'problem'=>'مشاجرة عائلية',
            'phone'=>'01000000000',
            'address'=>'الاسكندرية',
            'lat'=>40.05,
            'long'=>41.05,
            'status'=>'accepted',
            'lawyer_id'=>2,
            'client_id'=>2
        ]);
        SOSRequest::create([
            'problem'=>'حادثة بسيارة',
            'phone'=>'01000000654',
            'address'=>'قليوب',
            'lat'=>30.05,
            'long'=>31.05,
            'status'=>'completed',
            'lawyer_id'=>3,
            'client_id'=>3
        ]);


    }
}

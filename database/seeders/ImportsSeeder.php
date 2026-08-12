<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ImportsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('imports')->delete();

        $employeeCode = $this->ensureEmployees();
        $this->ensureEmployeeSettings();
        $supplements = $this->ensureSupplements();
        $snacks = $this->ensureSnacks();

        $quantityByCode = [
            '47299193'   => 50,
            '1739744506' => 40,
            '1520393376' => 2,
            '1627129830' => 25,
            '1205345473' => 15,
            '642692012'  => 0,
            '1458766887' => 120,
        ];

        $index = 0;
        foreach ($supplements as $supplement) {
            DB::table('imports')->insert([
                'code'             => (string) (1000000001 + $index++),
                'code_employee'    => $employeeCode,
                'name'             => $supplement->name,
                'state'            => 'import',
                'type'             => 'supplement',
                'amount'           => $supplement->amount,
                'quantity'         => $quantityByCode[$supplement->code] ?? 50,
                'code_supplements' => $supplement->code,
                'code_snaks'       => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
        foreach ($snacks as $snack) {
            DB::table('imports')->insert([
                'code'             => (string) (1000000001 + $index++),
                'code_employee'    => $employeeCode,
                'name'             => $snack->name,
                'state'            => 'import',
                'type'             => 'snacks',
                'amount'           => $snack->amount,
                'quantity'         => $quantityByCode[$snack->code] ?? 50,
                'code_supplements' => null,
                'code_snaks'       => $snack->code,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }

    private function ensureEmployees(): string
    {
        $employee = DB::table('employees')->first();
        if ($employee) {
            return $employee->code;
        }
        DB::table('employees')->insert([
            'code'          => '1469301573',
            'fname'         => 'Kareem',
            'lname'         => 'Abogabal',
            'job_role'      => 'admin',
            'phone'         => '01000000000',
            'img'           => null,
            'email'         => 'kareemabogabal41@gmail.com',
            'password'      => Hash::make('123456'),
            'documentation' => 'false',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        return '1469301573';
    }

    private function ensureEmployeeSettings(): void
    {
        $employees = DB::table('employees')->get();
        foreach ($employees as $employee) {
            $exists = DB::table('setting_employees')
                ->where('code_employee', $employee->code)
                ->exists();
            if (!$exists) {
                DB::table('setting_employees')->insert([
                    'code'            => (string) rand(100000, time()),
                    'code_employee'   => $employee->code,
                    'class_reminders' => true,
                    'login_alerts'    => false,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }
        }
    }

    private function ensureSupplements()
    {
        $supplements = DB::table('supplements')->get();
        if ($supplements->isNotEmpty()) {
            return $supplements;
        }
        $data = [
            ['code' => '47299193', 'name' => 'Whey protein', 'amount' => '1500', 'description' => 'Whey protein is the ideal choice for supporting muscle growth and accelerating recovery after exercise. It combines high quality and rapid absorption, making it the number one choice for athletes.', 'img' => '2025-10-8_12-31_AM.jpg'],
            ['code' => '1739744506', 'name' => 'ISO ADD', 'amount' => '1500', 'description' => 'A sack of ISO Add protein delivers high-quality whey isolate formulated for rapid absorption and lean muscle support.', 'img' => '2025-10-8_12-38_AM.jpg'],
            ['code' => '1520393376', 'name' => 'Whey protein ISO', 'amount' => '1500', 'description' => 'A sack of ISO Add protein delivers high-quality whey isolate formulated for rapid absorption and lean muscle support.', 'img' => '2025-11-12_2-11_PM.jpg'],
            ['code' => '1627129830', 'name' => 'IntraPro', 'amount' => '1500', 'description' => 'A tub of IntraPro provides an intra-workout blend to sustain energy and support endurance during training.', 'img' => '2025-10-8_12-39_AM.jpg'],
            ['code' => '1205345473', 'name' => 'Total War', 'amount' => '1500', 'description' => 'Total War blends turn-based empire management with real-time tactical battles.', 'img' => '2025-10-8_12-40_AM.jpg'],
            ['code' => '642692012', 'name' => 'creatine', 'amount' => '1850', 'description' => 'Creatine to boost strength and support high-intensity training performance.', 'img' => '2025-11-12_3-03_PM.jpg'],
        ];
        foreach ($data as $supplement) {
            DB::table('supplements')->insert([
                'code'        => $supplement['code'],
                'name'        => $supplement['name'],
                'description' => $supplement['description'],
                'img'         => $supplement['img'],
                'amount'      => $supplement['amount'],
                'discount'    => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
        return DB::table('supplements')->get();
    }

    private function ensureSnacks()
    {
        $snacks = DB::table('snacks')->get();
        if ($snacks->isNotEmpty()) {
            return $snacks;
        }
        DB::table('snacks')->insert([
            'code'       => '1458766887',
            'name'       => 'waters',
            'amount'     => '15',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return DB::table('snacks')->get();
    }
}

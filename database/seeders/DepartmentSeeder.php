<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name'=>'Agriculture Department',
            ],
            [
                'name'=>'Animal Husbandry and Veterinary Department',
            ],
            [
                'name'=>'Co-operation Department',
            ],
            [
                'name'=>'Disaster management and Rehabilitation Department',
            ],
            [
                'name'=>'District Council Affairs Department',
            ],
            [
                'name'=>'Economics and Statistics Department',
            ],
            [
                'name'=>'Election Department',
            ],
            [
                'name'=>'Environment and Forest Department',
            ],
            [
                'name'=>'Excise and Narcotic Department',
            ],
            [
                'name'=>'Finance Department',
            ],
            [
                'name'=>'Fisheries Department',
            ],
            [
                'name'=>'Food, Civil Supplies and Consumer Affairs Department',
            ],
            [
                'name'=>'General Administration Department',
            ],
            [
                'name'=>'Health and Family Welfare Department',
            ],
            [
                'name'=>'Higher and Technical Education Department',
            ],
            [
                'name'=>'Home Department',
            ],
            [
                'name'=>'Horticulture Department',
            ],
            [
                'name'=>'Industries Department',
            ],
            [
                'name'=>'Information and Communication Technology Department',
            ],
            [
                'name'=>'Information and Public Relations Department',
            ],
            [
                'name'=>'Land Revenue and Settlement Department',
            ],
            [
                'name'=>'Law and Judicial Department',
            ],
            [
                'name'=>'Local Administration Department',
            ],
            [
                'name'=>'Minor Irrigation Department',
            ],
            [
                'name'=>'Personnel and Administrative Reforms Department',
            ],
            [
                'name'=>'Planning and Programme Implementation Department',
            ],
            [
                'name'=>'Police Department',
            ],
            [
                'name'=>'Political and Cabinet Department',
            ],
            [
                'name'=>'Power and Electricity Department',
            ],
            [
                'name'=>'Prisons Department',
            ],
            [
                'name'=>'Public Health Engineering Department',
            ],
            [
                'name'=>'Public Works Department',
            ],
            [
                'name'=>'Rural Development Department',
            ],
            [
                'name'=>'Sainik Welfare & Resettlement Department',
            ],
            [
                'name'=>'School Education Department',
            ],
            [
                'name'=>'Sericulture Department',
            ],
            [
                'name'=>'Social Welfare Department',
            ],
            [
                'name'=>'Soil and Water Conservation Department',
            ],
            [
                'name'=>'Sports and Youth Services Department',
            ],
            [
                'name'=>'Taxation Department',
            ],
            [
                'name'=>'Tourism Department',
            ],
            [
                'name'=>'Trade and Commerce Department',
            ],
            [
                'name'=>'Transport Department',
            ],
            [
                'name'=>'Urban Development and Poverty Alleviation Department',
            ],
            [
                'name'=>'Vigilance Department',
            ],
            [
                'name'=>'DC Aizawl',
            ],
            [
                'name'=>'DC Lunglei',
            ],
            [
                'name'=>'DC Siaha',
            ],
            [
                'name'=>'DC Champhai',
            ],
            [
                'name'=>'DC Serchhip',
            ],
            [
                'name'=>'DC Kolasib',
            ],
            [
                'name'=>'DC Lawngtlai',
            ],
            [
                'name'=>'DC Mamit',
            ],
            [
                'name'=>'DC Saitual',
            ],
            [
                'name'=>'DC Khawzawl',
            ],
            [
                'name'=>'DC Hnahthial',
            ],
        ];

        foreach($departments as $department){
            Department::create($department);
        }
    }
}
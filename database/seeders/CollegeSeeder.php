<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\College;
use Illuminate\Database\Seeder;

class CollegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colleges = [
            [
                'name' => 'College of Humanities and Social Sciences (CHUSS)',
                'description' => 'Focuses on humanities, social sciences, and related disciplines.',
                'image' => null,
            ],
            [
                'name' => 'College of Veterinary Medicine, Animal Resources and Biosecurity (CoVAB)',
                'description' => 'Dedicated to veterinary medicine and animal resources.',
                'image' => null,
            ],
            [
                'name' => 'College of Business and Management Sciences (CoBAMS)',
                'description' => 'Offers programs in business, economics, and management.',
                'image' => null,
            ],
            [
                'name' => 'College of Agricultural and Environmental Sciences (CAES)',
                'description' => 'Specializes in agriculture, forestry, and environmental sciences.',
                'image' => null,
            ],
            [
                'name' => 'College of Natural Sciences (CONAS)',
                'description' => 'Covers natural sciences including physics, chemistry, and biology.',
                'image' => null,
            ],
            [
                'name' => 'College of Education and Sports (CES)',
                'description' => 'Provides education and sports science programs.',
                'image' => null,
            ],
            [
                'name' => 'College of Engineering, Design, Art and Technology (CEDAT)',
                'description' => 'Encompasses engineering, design, arts, and technology.',
                'image' => null,
            ],
            [
                'name' => 'College of Health Sciences (CHS)',
                'description' => 'Focuses on medicine, dentistry, and health sciences.',
                'image' => null,
            ],
            [
                'name' => 'School of Law',
                'description' => 'Offers legal education and training.',
                'image' => null,
            ],
        ];

        foreach ($colleges as $data) {
            $college = College::create($data);
            $college->slug = \Illuminate\Support\Str::slug($college->name);
            $college->save();
        }
    }
}

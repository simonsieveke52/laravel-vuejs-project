<?php

namespace App\Imports;

use App\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class CategoryImport implements ToCollection
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        $total = 0;
        $errors = 0;
        $greatGrand = 'greatGrand';
        $grand = 'grand';
        $parent = 'parent';
        $child = 'child';

        // BEGIN Creating the main nav bar top items as categories from Col 0 in csv**************
        $rows->each(function ($row, $index) use (&$total, &$errors, &$greatGrand, &$grand, &$parent, &$child) {
            if ($index < 1) {
                return;
            }
            if ($index <= 145) {
                try {
                    if ($row[0] != null) {
                        $greatGrand = $row[0];
                        Category::create([
                            'slug' => Str::slug($row[0]),
                            'name' => $row[0],
                            'description' => $row[2],
                            'on_navbar' => 1,
                            'dropdown_image' => 'storage/categories/' . $row[0] . '_dropdown.jpg',
                            'cover' => 'storage/categories/' . strtolower($row[0]) . '-cover.jpg'
                        ]);
                    }

                    if ($row[1] != null) {
                        $grand = $row[1];

                        $directParent = Category::where('name', $greatGrand)->first();
                        Category::create([
                            'slug' => $directParent->name."_".Str::slug($row[1]),
                            'name' => $row[1],
                            'parent_id' => $directParent->id,
                            'on_navbar' => 1,
                            'dropdown_image' => 'storage/categories/' . $row[0] . '_dropdown.jpg',
                            'cover' => 'storage/categories/' . strtolower($row[0]) . '-cover.jpg'
                        ]);
                    }

                    $total++;
                } catch (\Exception $e) {
                    $errors++;
                    logger(Str::limit($e->getMessage(), 150));
                }
            }
        });
        // END Creating the main nav bar top items as categories from Col 0 in csv**************
    }
}

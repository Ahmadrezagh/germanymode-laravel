<?php

namespace Database\Seeders;

use App\Domains\Catalog\Models\Brand;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\Warehouse;
use Illuminate\Database\Seeder;

class ShopMetaSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = [
            'انبار خالی', 'gamazon', 'سرویس خانه', 'ماشین الات', 'اسباب بازی', 'سگ',
            'حیوانات خانگی', 'مواد غذایی', 'گوشی موبایل', 'طبی و سلامت', 'لباس نوزاد',
            'کفش مردانه', 'لپ تاپ', 'بازی رایانه ای', 'ابزار الات', 'ارایشی', 'لباس مردانه',
            'کوهنوردی', 'بهداشتی برقی', 'اسکوتر', 'کتانی', 'عینک', 'ساعت', 'زیور آلات',
            'کیف زنانه', 'کفش زنانه', 'لباس زنانه', 'برقی خانگی', 'برقی آشپزخانه', 'انبار تست',
        ];

        foreach ($warehouses as $name) {
            Warehouse::query()->firstOrCreate(['name' => $name]);
        }

        $brands = [
            'برند تست', 'فیلیپس', 'BOSCH آلمان', 'AEG آلمان', 'prettylittlething انگلیس',
            'نینجا آمریکا', 'اسمگ ایتالیا', 'دایسون انگلستان', 'گاستروبک آلمان', 'اشتیل',
            'سامسونگ', 'Kenwood', 'Delonghi', 'Siemens', 'Miele',
        ];

        foreach ($brands as $name) {
            Brand::query()->firstOrCreate(['name' => $name]);
        }

        $warehouseId = Warehouse::query()->where('name', 'برقی آشپزخانه')->value('id')
            ?? Warehouse::query()->value('id');
        $brandId = Brand::query()->where('name', 'فیلیپس')->value('id')
            ?? Brand::query()->value('id');

        Product::query()->whereNull('warehouse_id')->limit(200)->update([
            'warehouse_id' => $warehouseId,
            'brand_id' => $brandId,
            'status' => 'موجود',
        ]);

        Product::query()->whereEffectivePriceGreaterThan(0)->whereColumn('regular_price', '>', 'discounted_price')->where('discounted_price', '>', 0)
            ->limit(40)
            ->update(['is_special_discount' => true]);

        Product::query()->whereEffectivePriceGreaterThan(0)->latest('id')->limit(30)
            ->update(['is_featured' => true]);
    }
}

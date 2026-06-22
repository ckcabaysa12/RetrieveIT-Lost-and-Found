<?php

use App\Models\Item;
use App\Models\ItemImage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Item::query()
            ->whereNotNull('image')
            ->each(function (Item $item) {
                ItemImage::create([
                    'item_id' => $item->id,
                    'path' => $item->image,
                    'sort_order' => 0,
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_images');
    }
};

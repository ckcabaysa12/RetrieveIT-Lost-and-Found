<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use DOMDocument;

class ReportXmlService
{
    public function buildDom(): DOMDocument
    {
        $userStats = [
            'total' => User::where('role', 'user')->count(),
            'verified' => User::where('role', 'user')->where('is_verified', true)->count(),
            'pending' => User::where('role', 'user')->where('verification_status', 'pending')->count(),
        ];

        $itemStats = [
            'lost' => Item::where('type', 'lost')->count(),
            'found' => Item::where('type', 'found')->count(),
            'returned' => Item::where('status', 'returned')->count(),
            'pending_claim' => Item::where('status', 'pending_claim')->count(),
        ];

        $claimStats = [
            'pending' => Item::where('status', 'pending_claim')->count(),
            'approved' => Claim::where('status', 'approved')->count(),
            'rejected' => Claim::where('status', 'rejected')->count(),
        ];

        $byCategory = Category::withCount('items')->orderByDesc('items_count')->get();

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $root = $dom->createElement('retrieveit-report');
        $root->setAttribute('generated-at', now()->toIso8601String());
        $root->setAttribute('system', config('app.name'));
        $dom->appendChild($root);

        $users = $dom->createElement('users');
        foreach ($userStats as $key => $value) {
            $node = $dom->createElement($key, (string) $value);
            $users->appendChild($node);
        }
        $root->appendChild($users);

        $items = $dom->createElement('items');
        foreach ($itemStats as $key => $value) {
            $node = $dom->createElement($key, (string) $value);
            $items->appendChild($node);
        }
        $root->appendChild($items);

        $claims = $dom->createElement('claims');
        foreach ($claimStats as $key => $value) {
            $node = $dom->createElement($key, (string) $value);
            $claims->appendChild($node);
        }
        $root->appendChild($claims);

        $categories = $dom->createElement('categories');
        foreach ($byCategory as $category) {
            $categoryNode = $dom->createElement('category');
            $categoryNode->setAttribute('id', (string) $category->id);
            $categoryNode->setAttribute('name', $category->name);
            $categoryNode->setAttribute('count', (string) $category->items_count);
            $categories->appendChild($categoryNode);
        }
        $root->appendChild($categories);

        return $dom;
    }

    public function toXmlString(): string
    {
        return $this->buildDom()->saveXML();
    }
}

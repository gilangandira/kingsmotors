<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SortingItemController extends Controller
{
    /////////Controller Handle Category/////////////////
    public function showCategory()
    {
        $categories = Category::paginate(10);
        $allCategories = Category::all(); // Load all categories
        return view('sorting.category', compact('categories', 'allCategories'));
    }
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Category added successfully!');
    }
    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Category updated successfully!');
    }
    public function destroyCategory(Request $request, $id)
    {
        // Validate that the reassignment category is provided
        $request->validate([
            'reassign_category_id' => 'required|exists:categories,id',
        ]);

        // Find the category to be deleted
        $category = Category::findOrFail($id);

        // Reassign all related items (e.g., products, inventory, etc.) to the selected category
        $newCategoryId = $request->input('reassign_category_id');

        // Update related items that belong to the category being deleted
        Inventory::where('category_id', $category->id)->update(['category_id' => $newCategoryId]);

        // Once reassigned, delete the category
        $category->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Category deleted successfully, and related items have been reassigned!');
    }





    /////////Controller Handle Location/////////////////
    public function showLocation()
    {
        $locations = Location::paginate(10);
        $allLocations = Location::all();
        return view('sorting.location', compact('locations', 'allLocations'));
    }
    public function storeLocation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Location::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Location added successfully!');
    }
    public function updateLocation(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $location = Location::findOrFail($id);
        $location->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Category updated successfully!');
    }
    public function destroyLocation(Request $request, $id)
    {
        // Validate that the reassignment category is provided
        $request->validate([
            'reassign_location_id' => 'required|exists:categories,id',
        ]);

        // Find the category to be deleted
        $location = Location::findOrFail($id);

        // Reassign all related items (e.g., products, inventory, etc.) to the selected category
        $newLocationId = $request->input('reassign_location_id');

        // Update related items that belong to the category being deleted
        Inventory::where('location_id', $location->id)->update(['location_id' => $newLocationId]);

        // Once reassigned, delete the category
        $location->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Location deleted successfully, and related items have been reassigned!');
    }






    /////////Controller Handle Location/////////////////
    public function showBrands()
    {
        $brands = Brand::paginate(10);
        $allBrands = Brand::all();
        return view('sorting.brand', compact('brands', 'allBrands'));
    }
    public function storeBrand(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Brand::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Brand added successfully!');
    }
    public function updateBrand(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $brand = Brand::findOrFail($id);
        $brand->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Brand updated successfully!');
    }
    public function destroyBrand(Request $request, $id)
    {
        // Validate that the reassignment category is provided
        $request->validate([
            'reassign_brand_id' => 'required|exists:brands,id',
        ]);

        // Find the category to be deleted
        $brand = Brand::findOrFail($id);

        // Reassign all related items (e.g., products, inventory, etc.) to the selected category
        $newBrandId = $request->input('reassign_brand_id');

        // Update related items that belong to the category being deleted
        Inventory::where('brand_id', $brand->id)->update(['brand_id' => $newBrandId]);

        // Once reassigned, delete the category
        $brand->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'brand deleted successfully, and related items have been reassigned!');
    }

}


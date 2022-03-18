<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\Product as ResourceProduct;

class ProductController extends Controller
{
    /**
     * Creates A Product
     * @param Request $request
     * 
     * @return [type]
     */
    public function createProduct(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:100',
            'unit_price' => 'required|numeric|max:100',
            'quantity_per_unit' => 'required|numeric',
            'image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',

        ]);

        if ($validator->fails()) {
            return $this->failedResponse($validator->errors());
        }

        if ($request->has('image') && $image = $request->file('image')) {
            $imageDestinationPath = '/images/productimages/' . $request->product_name . '/';
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();

            if ($request->image->move(public_path($imageDestinationPath), $imageName)) {
                $request->image = $imageDestinationPath . $imageName;
                $input['image'] = $imageDestinationPath . $imageName;
            }
        }
        $input['product_id'] = Str::uuid()->toString();
        $product = Product::create($input);

        return $this->successResponse('Done', 'Product Created Successfully');
    }




    /**
     * Lists All Products
     * @param Request $request
     * 
     * @return [type]
     */
    public function getAllProduct(Request $request)
    {

        $limit = 15;

        $allProducts = Product::all();
        $fetchedProducts = ResourceProduct::collection($allProducts);
        if (count($fetchedProducts) > 0) {
            return $this->successResponse($this->paginate($fetchedProducts, $limit), 'All Products have been successfully Retrieved');
        } else {
            return $this->failedResponse('No Products Found', ['error' => 'No Products Found']);
        }
    }

    /**
     * Get a Specific Product
     * @param Request $request
     * 
     * @return [type]
     */
    public function getSpecificProduct(Request $request)
    {
        $productDetails = null;

        $validator = Validator::make($request->all(), [
            'id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->failedResponse($validator->errors());
        }

        if ($request->has('id')) {
            $productDetails = new ResourceProduct(Product::findOrFail($request->id));
        }

        if ($productDetails !== null) {
            return $this->successResponse($productDetails, 'Product Fetching Successful');
        } else {
            return $this->failedResponse('NullException', ['error' => 'No Product Found']);
        }
    }


    /**
     * Updates A Product
     * @param Request $request
     * 
     * @return [type]
     */
    public function updateProduct(Request $request)
    {
        if ($request->has('id')) {

            $productDetails = Product::find($request->id);
            if (is_null($productDetails)) {
                return $this->failedResponse('Invalid Product Identifier', ['error' => 'No Product Found']);
            }
        } else {
            return $this->failedResponse('Invalid Product Identifier', ['error' => 'No/Invalid Identifier Received']);
        }

        if ($request->has('product_name')) {
            if ($request->validate([
                'product_name' => 'string|max:100'
            ])) {
                $productDetails->product_name = $request->product_name;
            } else {
                return $this->failedResponse('Invalid input' . $request->product_name);
            }
        }
        if ($request->has('full_name')) {
            if ($request->validate([
                'full_name' => 'string|max:100'
            ])) {
                $productDetails->full_name = $request->full_name;
            } else {
                return $this->failedResponse('Invalid input' . $request->full_name);
            }
        }
        if ($request->has('email')) {
            if ($request->validate([
                'email' => 'string|unique:products'
            ])) {
                $productDetails->email = $request->email;
            } else {
                return $this->failedResponse('Invalid input' . $request->email);
            }
        }
        if ($request->has('phone')) {
            if ($request->validate([
                'phone' => 'string|unique:products'
            ])) {
                $productDetails->phone = $request->phone;
            } else {
                return $this->failedResponse('Invalid input' . $request->phone);
            }
        }
        if ($request->has('password')) {
            if ($request->validate([
                'password' => 'string|min:6'
            ])) {
                $productDetails->password = bcrypt($request->password);
            } else {
                return $this->failedResponse('Invalid input' . $request->password);
            }
        }

        if ($image = $request->file('updated_image')) {
            $imageDestinationPath = '/images/productimages/' . $request->product_name . '/';
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();

            $productOldThumbnail = $productDetails->image;
            if (File::exists($productOldThumbnail)) {
                File::delete($productOldThumbnail);
            }
            if ($request->image->move(public_path($imageDestinationPath), $imageName)) {
                $request->image = $imageDestinationPath . $imageName;
                $input['image'] = $imageDestinationPath . $imageName;
            } else {
                return $this->failedResponse('I/O Error', 'Could not Update Image');
            }
            $productDetails->updated_image = $imageDestinationPath . $imageName;
        }

        $productDetails->save();

        return $this->successResponse(
            'Data Updated',
            'Product updated successfully'
        );
    }

    /**
     * Deletes the Product
     * @param Request $request
     *
     * @return [type]
     */
    public function deleteProduct(Request $request)
    {
        $productDetails = Product::find($request->id);
        if ($productDetails) {
            $productDetails->delete();
        } else {
            return $this->failedResponse('Invalid Identifier', ['error' => 'Invalid Identifier Received']);
        }

        return $this->successResponse('Done', 'Data deleted Successfully');
    }

    /**
     * @param mixed $items
     * @param mixed $perPage
     * @param null $page
     * @param array $options
     * 
     * @return [type]
     */
    public function paginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}

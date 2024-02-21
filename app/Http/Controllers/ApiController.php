<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Throwable;

class ApiController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'username' => 'required|string|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
            ]);
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'data' => $user,
                'token' => $token,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation error',
                'message' => $e->errors()
            ], 400);
        } catch (Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);
            $user = User::where('username', $request->username)->orWhere('email', $request->username)->first();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                ], 404);
            } else if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Incorrect password',
                ], 401);
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'data' => $user,
                'token' => $token,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Logged out successfully'
            ], 200);
        } catch (Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function getOutlet(Request $request)
    {
        try {
            $outlet = Outlet::where('code', $request->code)->first();
            if (!$outlet) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Code not found',
                ], 404);
            }
            return response()->json([
                'status' => 'success',
                'data' => $outlet
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getMenu(Request $request)
    {
        try {
            $outlet = Outlet::where('code', $request->code)->first();
            if (!$outlet) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Code not found',
                ], 404);
            }
            $menus = $outlet->menus;
            return response()->json([
                'status' => 'success',
                'data' => $menus
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function order(Request $request)
    {
        $user = auth()->user();
        try {
            $request->validate([
                'code' => 'required|string',
                'menu_id' => 'required|array',
                'menu_id.*' => 'exists:menus,id',
                'quantity' => 'required|array',
                'quantity.*' => 'numeric|min:1',
            ]);

            $outlet = Outlet::where('code', $request->code)->first();
            if (!$outlet) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Code not found',
                ], 404);
            }

            $transaction = $user->transactions()->create([
                'outlet_id' => $outlet->id,
            ]);

            $totalPrice = 0;
            foreach ($request->menu_id as $key => $menuId) {
                $menu = Menu::find($menuId);
                if (!$menu) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Menu with ID {$menuId} not found",
                    ], 404);
                }
                $quantity = $request->quantity[$key];
                $transaction->orders()->create([
                    'menu_id' => $menu->id,
                    'quantity' => $quantity,
                ]);
                $totalPrice += $menu->price * $quantity;
            }

            $transaction->update([
                'total_price' => $totalPrice,
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $transaction,
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function getOrders(Request $request)
    {
        $user = auth()->user();
        $transactions = $user->transactions;
        return response()->json([
            'status' => 'success',
            'data' => $transactions
        ], 200);
    }
    public function getOrder(Request $request)
    {
        $user = auth()->user();
        $transaction = $user->transactions()->find($request->id);
        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $transaction,
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreBook;
use App\Http\Requests\BookRentingRequest;
use App\Models\Book;
use App\Models\BookRenting;
use App\Models\User;

class BookController extends Controller
{
    public function list(Request $request)
    {
        $books = Book::get();
        return api_response_send('success', '', $books, 200);
    }

    public function store(StoreBook $request)
    {
        DB::beginTransaction();
        try {
            $book =  new  Book();
            $book->book_name = $request['book_name'];
            $book->author = $request['author'];

            $imageName = time() . '.' . $request->cover_image->extension();
            $request->cover_image->move(public_path('cover_images'), $imageName);
            $book->cover_image = $imageName;
            $book->save();
            DB::commit();

            return api_response_send('success', 'Book Added successfully.', $book, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return api_response_send('error', $e->getMessage(), [], 500);
        }
    }

    public function edit($id)
    {
        $book = Book::where('id', $id)->first();
        return api_response_send('success', '', $book, 200);
    }


    public function update(StoreBook $request, Book $book)
    {
        DB::beginTransaction();
        try {
            if (!empty($book)) {
                $book->book_name = $request['book_name'];
                $book->author = $request['author'];

                if ($request->cover_image) {
                    $imageName = time() . '.' . $request->cover_image->extension();
                    $request->cover_image->move(public_path('cover_images'), $imageName);
                    $book->cover_image = $imageName;
                }
                $book->save();
                DB::commit();
                return api_response_send('success', 'Book updated successfully.', $book, 200);
            } else {
                return api_response_send('error', 'Data not Found.', '', 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return api_response_send('error', $e->getMessage(), [], 500);
        }
    }

    public function delete(Book $book)
    {
        DB::beginTransaction();
        try {
            BookRenting::where('book_id', $book->id)->delete();
            $book->delete();
            DB::commit();
            return api_response_send('success', 'Book deleted successfully.', [], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return api_response_send('error', $e->getMessage(), [], 500);
        }
    }


    public function bookRenting(Book $book, User $user)
    {
        DB::beginTransaction();
        try {
            $book_renting = new BookRenting();
            $book_renting->user_id = $book->id;
            $book_renting->book_id = $user->id;
            $book_renting->save();
            DB::commit();
            return api_response_send('success', 'Book Rented successfully.', $book_renting, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return api_response_send('error', $e->getMessage(), [], 500);
        }
    }

    public function bookReturn(Request $request)
    {
        DB::beginTransaction();
        try {
            $book_renting = BookRenting::where('id', $request['id'])->first();
            $book_renting->return_date = $request['return_date'];
            $book_renting->save();
            DB::commit();
            return api_response_send('success', 'Book Return successfully.', [], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return api_response_send('error', $e->getMessage(), [], 500);
        }
    }
}

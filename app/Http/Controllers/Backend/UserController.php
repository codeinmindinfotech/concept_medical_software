<?php
namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use App\Rules\UniquePerCompany;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
    
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View|string
    {
        $user = auth()->user();
        $query = User::with(['creator', 'updater'])->companyOnly();
        $data = $query->latest()->paginate(5);

        if ($request->ajax()) {
            return view('users.list', compact('data'))->render();
        }
        return view('users.index',compact('data'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $query = Role::where('guard_name', 'web');
        if (has_role('manager')) {
            $query->where('name', 'manager');
        } 
        $roles = $query->pluck('name','name')->all(); 

        return view('users.create',compact('roles'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $companyId = auth()->user()->company_id;

        $this->validate($request, [
            'name' => 'required',
            'email' => ['required', 'email', new UniquePerCompany('users', 'email', $companyId)],
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['created_by'] = auth()->id();
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
    
        return redirect(guard_route('users.index'))
                        ->with('success','User created successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('users.show',compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $userRole = $user->roles->pluck('name','name')->all();
        $query = Role::where('guard_name', 'web');
        if (has_role('manager')) {
            $query->where('name', 'manager');
        } 
        $roles = $query->pluck('name','name')->all(); 
        return view('users.edit',compact('user','roles','userRole'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $companyId = auth()->user()->company_id;
        $this->validate($request, [
            'name' => 'required',
            'email' => ['required', 'email', new UniquePerCompany('users', 'email', $companyId, $id)],
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user = User::find($id);
        $input['updated_by'] = auth()->id();
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));
    
        return redirect(guard_route('users.index'))
                        ->with('success','User updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect(guard_route('users.index'))
                        ->with('success','User deleted successfully');
    }
}
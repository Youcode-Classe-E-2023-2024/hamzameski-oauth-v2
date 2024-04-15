@extends('shared.layout')

@section('content')
    <section id="dashboard-container" class="bg-yellow-500 p-6 relative overflow-auto">
        <!-- dashboard container -->
        <section>
            <div class="min-h-screen bg-white">
                <div class="align-middle rounded-tl-lg rounded-tr-lg inline-block w-full py-4 overflow-hidden bg-white shadow-lg px-12">
                    <div class="flex justify-between">
                        <div class="inline-flex border rounded w-7/12 px-2 lg:px-6 h-12 bg-transparent">
                            <div class="flex flex-wrap items-stretch w-full h-full mb-6 relative">
                                <div class="flex">
                                    <span class="flex items-center leading-normal bg-transparent rounded rounded-r-none border border-r-0 border-none lg:px-3 py-2 whitespace-no-wrap text-grey-dark text-sm">
                                        <svg width="18" height="18" class="w-4 lg:w-auto" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8.11086 15.2217C12.0381 15.2217 15.2217 12.0381 15.2217 8.11086C15.2217 4.18364 12.0381 1 8.11086 1C4.18364 1 1 4.18364 1 8.11086C1 12.0381 4.18364 15.2217 8.11086 15.2217Z" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M16.9993 16.9993L13.1328 13.1328" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                                <input type="text" class="flex-shrink flex-grow flex-auto leading-normal tracking-wide w-px flex-1 border border-none border-l-0 rounded rounded-l-none px-3 relative focus:outline-none text-xxs lg:text-xs lg:text-base text-gray-500 font-thin" placeholder="Search">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="align-middle inline-block min-w-full shadow overflow-hidden bg-white shadow-dashboard px-8 pt-3 rounded-bl-lg rounded-br-lg">
                    <table class="min-w-full">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">ID</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-blue-500 tracking-wider">Fullname</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-blue-500 tracking-wider">Roles</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-blue-500 tracking-wider">Assign role</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-blue-500 tracking-wider">delete user</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300">Edit user</th>
                        </tr>
                        </thead>

                        <tbody id="users-container" class="bg-white">

                        </tbody>

                    </table>
                </div>
            </div>
        </section>


        {{--    display roles overlay    --}}
        <section id="display-roles-overlay" class="h-full w-full absolute top-0 left-0 bg-black bg-opacity-50 backdrop-blur-sm hidden flex items-center justify-center">

            {{-- users popup  --}}
            <div class="popup  op-[18%] rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-200 sm:mx-0 sm:h-10 sm:w-10" style="">
                        </div>
                        <div class=" mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="popup-title">User Roles</h3>
                            <div class="mt-2 ">
                                <ul id="checkboxes-container" class="h-[150px] w-full overflow-auto text-sm text-gray-500">
                                    <li>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" class="checkbox h-5 w-5 text-green-500">
                                            <span class="ml-2">Role 1</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" class="checkbox h-5 w-5 text-green-500">
                                            <span class="ml-2">Role 2</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" class="checkbox h-5 w-5 text-green-500">
                                            <span class="ml-2">Role 3</span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" id="unset-btn" class=" inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Unset roles</button>
                    <button type="button" class="cancel-display-roles-popup mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                </div>

            </div>
        </section>

        {{--    assign roles overlay    --}}
        <section id="assign-roles-overlay" class="h-full w-full absolute top-0 left-0 bg-black bg-opacity-50 backdrop-blur-sm hidden flex items-center justify-center">

            {{-- users popup  --}}
            <div class="assign-roles-popup  op-[18%] rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-purple-200 sm:mx-0 sm:h-10 sm:w-10" style="">
                        </div>
                        <div class=" mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="popup-title">List of Roles</h3>
                            <div class="mt-2 ">
                                <ul id="assign-roles-checkboxes-container" class="h-[150px] w-full overflow-auto text-sm text-gray-500">
                                    <li>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" class="checkbox h-5 w-5 text-green-500">
                                            <span class="ml-2">Role 1</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" class="checkbox h-5 w-5 text-green-500">
                                            <span class="ml-2">Role 2</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" class="checkbox h-5 w-5 text-green-500">
                                            <span class="ml-2">Role 3</span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" id="assign-btn" class=" inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:ml-3 sm:w-auto">Assign as role</button>
                    <button type="button" class="cancel-assign-roles-popup mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                </div>

            </div>
        </section>

        {{--    delete user overlay    --}}
        <section id="delete-user-overlay" class="h-full w-full absolute top-0 left-0 bg-black bg-opacity-50 backdrop-blur-sm hidden flex items-center justify-center">

            {{-- users popup  --}}
            <div class="delete-user-popup  op-[18%] rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-200 sm:mx-0 sm:h-10 sm:w-10" style="">
                        </div>
                        <div class=" mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="popup-title">Delete User Account</h3>
                            <div class="mt-2 ">
                                Are you Sure? Once You delete this account it could not be reset,
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" id="delete-btn" class=" inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">delete user</button>
                    <button type="button" class="cancel-delete-user-popup mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                </div>
            </div>
        </section>

        {{--    edit user overlay    --}}
        <section id="edit-user-overlay" class="h-full w-full absolute top-0 left-0 bg-black bg-opacity-50 backdrop-blur-sm hidden flex items-center justify-center">

            {{-- users popup  --}}
            <form id="edit-form" class="edit-user-popup  op-[18%] rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-200 sm:mx-0 sm:h-10 sm:w-10" style="">
                        </div>
                        <div class=" mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="popup-title">User Info</h3>
                            <div class="mt-2 ">
                                <div class="mb-4">
                                    <div id="email" class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></div>
                                </div>
                                <div class="mb-4">
                                    <div id="name" class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" class="cancel-edit-user-popup mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                </div>

            </form>
        </section>

    </section>


    <script src="{{asset('dashboard/js/users-table.js')}}"></script>
@endsection

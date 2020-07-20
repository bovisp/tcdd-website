@extends('layouts.app')

@section('content')

    <div class="py-16">
        <div 
            class="h-64 w-64 bg-red-500"
            id="square"
        ></div>

        <p>List of draggable items</p>

        <div
            class="alert alert-blue"
        >There are no draggable items</div>

        <button id="create-items" class="btn btn-blue">Create items</button>

        <div
            id="drag-list"
        ></div>
    </div>

@endsection

@section('jsbottom')

<script>
    document.addEventListener('DOMContentLoaded',function(){
        const items = []

        const square = document.getElementById('square')
        const createItemsBtn = document.getElementById('create-items')
        const dragListEl = document.getElementById('drag-list')

        square.addEventListener('click', function (e) {
            newItem = document.createElement('div')

            newItem.innerHTML = `
                <div class="w-1/4">
                    <label for="drag-text" class="block font-bold text-gray-700 mb-1">
                        Enter drag text
                    </label>

                    <input
                        type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto block"
                    >
                </div>
            `

            dragListEl.appendChild(newItem)

            items.push({
                x: e.offsetX,
                y: e.offsetY
            })

            console.log(items)
        })
    })
</script>

@endsection

@extends('layouts.app')

@section('content')

    <div class="py-16">
        <div 
            class="bg-red-500 relative z-0"
            style="width: 700px; height: 400px;"
            id="square"
        ></div>

        <div
            id="drag-list"
            class="flex w-full flex-wrap"
        ></div>
    </div>

@endsection

@section('jsbottom')

<script src="https://unpkg.com/uuid@latest/dist/umd/uuidv4.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const items = []

        const square = document.getElementById('square')
        const createItemsBtn = document.getElementById('create-items')
        const dragListEl = document.getElementById('drag-list')
        let textInputs = document.querySelectorAll('#drag-list input')
        let newItemWidth = 0

        square.addEventListener('click', e => {
            let newItem = document.createElement('div')
            newItem.classList.add('w-1/2', 'p-4')
            let newItemVal = ''
            let id = uuidv4()


            newItem.innerHTML = `
                <label for="${id}" class="block font-bold text-gray-700 mb-1">
                    Enter drag text
                </label>

                <input
                    type="text"
                    id="${id}"
                    class="mr-4 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-full"
                >

                <span id="${id}-span" class="p-2 text-sm cursor-pointer" draggable="true"></span>
            `

            dragListEl.appendChild(newItem)

            items.push({
                id, 
                x: e.offsetX,
                y: e.offsetY
            })

            document.getElementById(id).addEventListener('keyup', async (e) => {
                let activeInput = items.find(i => i.id === id)

                activeInput.text = e.target.value

                let activeSpan = document.getElementById(id + '-span')

                activeSpan.textContent = e.target.value

                activeSpan.addEventListener('dragstart', e => {
                    activeSpan.style.opacity = '0.4'
                })

                let dropItem = document.getElementById(`${id}-drop`)

                if (dropItem) {
                    dropItem.textContent = e.target.value

                    if (activeSpan.offsetWidth > newItemWidth) {
                        console.log('here')
                        newItemWidth = activeSpan.offsetWidth

                        dropItems = document.querySelectorAll('.drops')

                        for (let item of dropItems) {
                            item.style.width = `${newItemWidth + 3}px`
                        }
                    }
                } else {
                    let newDropItem = document.createElement('div')

                    newDropItem.classList.add('drops', 'absolute', 'border', 'p-2', 'text-sm', 'z-10')

                    if (activeSpan.offsetWidth > newItemWidth) {
                        console.log('here')
                        newItemWidth = activeSpan.offsetWidth

                        dropItems = document.querySelectorAll('.drops')

                        for (let item of dropItems) {
                            item.style.width =`${newItemWidth + 3}px`
                        }
                   }

                   newDropItem.style.width = `${newItemWidth + 3}px`

                    newDropItem.style.height = `${activeSpan.offsetHeight}px`

                    newDropItem.textContent = e.target.value

                    newDropItem.style.top = `${activeInput.y}px`

                    newDropItem.style.left =  `${activeInput.x}px`

                    newDropItem.id =  `${id}-drop`

                    square.appendChild(newDropItem)
                }
            })
        })

        // textInputs.forEach(item => {
        //     item.addEventListener('keyup', e => {
        //         let activeInput = items.find(i => i.id === id)

        //         console.log(activeInput)
        //     })
        // })
    })
</script>

@endsection

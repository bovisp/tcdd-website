<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="card">
                        <div class="card-header">
                            <slot name="header">
                                default header
                            </slot>
                        </div>

                        <div class="card-body">
                            <slot name="body">
                                default body
                            </slot>
                        </div>

                        <div class="card-footer d-flex justify-content-end">
                            <slot name="footer">
                                <button 
                                    class="btn btn-primary" 
                                    @click="$emit('close')"
                                >OK</button>
                            </slot>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>
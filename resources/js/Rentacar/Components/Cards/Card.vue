<template>
    <div class="card-compact w-full max-w-md p-2.5 shadow-xl border-2 bg-white">
        <div class="card-body">
            <p>
                <slot name="title"></slot>
            </p>
            <slot></slot>
        </div>
    </div>
</template>

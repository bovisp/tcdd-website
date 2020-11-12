<template>
  <div class="flex justify-center mt-6">
    <button 
      class="btn btn-outline mx-2 text-gray-700"
      @click.prevent="$emit('start')"
    >
      <i class="fas fa-fast-backward"></i>
    </button>

    <button 
      class="btn btn-outline mx-2 text-gray-700"
      @click.prevent="$emit('backward')"
    >
      <i class="fas fa-backward"></i>
    </button>

    <button 
      class="btn btn-outline mx-2 text-gray-700"
      @click.prevent="togglePlay"
    >
      <i 
        class="fas"
        :class="playState"
      ></i>
    </button>

    <button 
      class="btn btn-outline mx-2 text-gray-700"
      @click.prevent="$emit('forward')"
    >
      <i class="fas fa-forward"></i>
    </button>

    <button 
      class="btn btn-outline mx-2 text-gray-700"
      @click.prevent="$emit('end')"
    >
      <i class="fas fa-fast-forward"></i>
    </button>
  </div>
</template>

<script>
export default {
  data () {
    return {
      playState: 'fa-play'
    }
  },

  methods: {
    togglePlay () {
      this.playState = this.playState === 'fa-play' ? 'fa-pause' : 'fa-play'

      this.$emit('playpause', {state: this.playState})
    }
  }
}
</script>
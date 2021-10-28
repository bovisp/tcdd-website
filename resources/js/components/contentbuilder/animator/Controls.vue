<template>
  <div class="flex justify-center mt-6">
    <b-button 
      class="mx-2"
      icon-right="skip-backward"
      @click.prevent="$emit('start')"
    ></b-button>

    <b-button 
      class="mx-2"
      icon-right="skip-previous"
      @click.prevent="$emit('backward')"
    ></b-button>

    <b-button 
      class="mx-2"
      :icon-right="playState"
      @click.prevent="togglePlay"
    ></b-button>

    <b-button 
      class="mx-2"
      icon-right="skip-next"
      @click.prevent="$emit('forward')"
    ></b-button>

    <b-button 
      class="mx-2"
      icon-right="skip-forward"
      @click.prevent="$emit('end')"
    ></b-button>
  </div>
</template>

<script>
export default {
  data () {
    return {
      playState: 'play'
    }
  },

  methods: {
    togglePlay () {
      this.playState = this.playState === 'play' ? 'pause' : 'play'

      this.$emit('playpause', {state: this.playState})
    }
  }
}
</script>
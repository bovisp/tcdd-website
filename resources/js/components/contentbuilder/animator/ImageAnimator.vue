<template>
  <div 
    class="p-2"
    :class="{ 'border rounded': standalone }"
  >
    <p 
      class="mb-1 text-center"
      v-if="image.timestamp"
    >
      <strong>{{ image.timestamp }}</strong>
    </p>

    <img 
      :src="`${urlBase}${image.file}`" 
      class="block mr-auto ml-auto"
      :class="{ 'img-fluid': fluid }"
    >

    <controls 
      @start="start"
      @backward="backward"
      @forward="forward"
      @end="end"
      @playpause="playpause"
    />
  </div>
</template>

<script>
import { orderBy, findIndex } from 'lodash-es'

export default {
  props: {
    files: {
        type: Array,
        required: true
    },
    standalone: {
      type: Boolean,
      required: false,
      default: false
    },
    fluid: {
      type: Boolean,
      required: false,
      default: false
    }
  },

  data () {
    return {
      part: [],
      image: {},
      handle: 0
    }
  },

  watch: {
      files () {
        this.init()
      }
  },

  methods: {
    start () {
      this.clearIntervalHandle()

      this.image = this.part[0]
    },

    end () {
      this.clearIntervalHandle()
      
      this.image = this.part[this.part.length - 1]
    },

    backward () {
      this.clearIntervalHandle()

      this.switchImage('backward')
    },

    forward () {
      this.clearIntervalHandle()

      this.switchImage()
    },

    playpause ({state}) {
      if (state === 'pause') {
        this.handle = setInterval(() => {
          this.switchImage()
        }, 500)

        return
      }
      
      this.clearIntervalHandle()
    },

    switchImage (direction = 'forward') {
      let index = findIndex(this.part, part => part.order === this.image.order)
      
      if (direction === 'forward' && index === this.part.length - 1) {
        this.image = this.part[0]

        return
      }

      if (direction === 'backward' && index === 0) {
        this.image = this.part[this.part.length - 1]

        return
      }

      if (direction === 'backward') {
        this.image = this.part[index - 1]

        return
      }

      this.image = this.part[index + 1]
    },

    clearIntervalHandle () {
      clearInterval(this.handle)
      
      this.handle = 0
    },

    init () {
      this.part = orderBy(this.files, ['order'], ['asc'])

      this.image = this.part[0]
    }
  },

  mounted () {
      this.init()

      window.events.$on('part:edit-cancel', () => {
        this.init()
      })
  }
}
</script>
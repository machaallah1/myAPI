<script setup lang="ts">

interface Props {
  isDialogVisible: boolean
  postTitle?: string
  postContent?: string
}

interface Emit {
  (e: 'update:isDialogVisible', value: boolean): void
  (e: 'update:postTitle', value: string): void
  (e: 'update:postContent', value: string): void
}

const props = withDefaults(defineProps<Props>(), {
  postTitle: '',
  postContent: ''
})

const emit = defineEmits<Emit>()

// Refs for form data
const currentPostTitle = ref('')
const currentPostContent = ref('')

// Reset the form and close the dialog
const onReset = () => {
  emit('update:isDialogVisible', false)
  currentPostTitle.value = ''
  currentPostContent.value = ''
}

// Submit the form and update the parent component
const onSubmit = () => {
  emit('update:isDialogVisible', false)
  emit('update:postTitle', currentPostTitle.value)
  emit('update:postContent', currentPostContent.value)
}

// Watch for prop changes to sync with form
watch(() => props, () => {
  currentPostTitle.value = props.postTitle
  currentPostContent.value = props.postContent
})
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 600"
    :model-value="props.isDialogVisible"
    @update:model-value="onReset"
  >
    <!-- Dialog close button -->
    <DialogCloseBtn @click="onReset" />

    <VCard class="pa-2 pa-sm-10">
      <VCardText>
        <!-- Title of the form -->
        <h4 class="text-h4 text-center mb-2">
          {{ props.postTitle ? 'Edit' : 'Add' }} Post
        </h4>
        <p class="text-body-1 text-center mb-6">
          {{ props.postTitle ? 'Edit' : 'Add' }} the post content as per your requirements.
        </p>

        <!-- Form starts here -->
        <VForm>
          <VAlert
            type="warning"
            title="Warning!"
            variant="tonal"
            class="mb-6"
          >
            <template #text>
              By {{ props.postTitle ? 'editing' : 'adding' }} the post title and content, you may modify the blog's structure.
            </template>
          </VAlert>

          <!-- Post title input -->
          <div class="d-flex gap-4 mb-6 flex-wrap flex-column flex-sm-row">
            <VTextField
              v-model="currentPostTitle"
              label="Post Title"
              placeholder="Enter Post Title"
            />
          </div>

          <!-- Post content input -->
          <div class="d-flex gap-4 mb-6 flex-wrap flex-column flex-sm-row">
            <VTextField
              v-model="currentPostContent"
              label="Post Content"
              placeholder="Enter Post Content"
              multiline
            />
          </div>

          <!-- Submit and reset buttons -->
          <div class="d-flex justify-center gap-4">
            <VBtn @click="onSubmit">
              {{ props.postTitle ? 'Update' : 'Add' }}
            </VBtn>
            <VBtn @click="onReset" text>
              Cancel
            </VBtn>
          </div>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>

<style lang="scss">
.permission-table {
  td {
    border-block-end: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
    padding-block: 0.5rem;
    padding-inline: 0;
  }
}
</style>

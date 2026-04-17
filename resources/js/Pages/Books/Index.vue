<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { ref, computed } from 'vue'
import { useForm, router, usePage, Link } from '@inertiajs/vue3'

const page = usePage()

// permission
const permissions = computed(() => page.props.auth.user?.permissions || [])
const can = (perm) => permissions.value.includes(perm)

const pinjam = (bookId) => {
    router.post('/peminjaman', {
        book_id: bookId
    }, {
        onSuccess: () => alert('Buku berhasil dipinjam'),
        onError: () => alert('Gagal meminjam buku')
    })
}

// props
const props = defineProps({
  books: Object,
  filters: Object
})

// state
const showModal = ref(false)
const isEdit = ref(false)
const selectedId = ref(null)
const search = ref(props.filters?.search || '')

// form
const form = useForm({
  judul: '',
  penulis: '',
  penerbit: '',
  tahun_terbit: '',
  isbn: '',
  stok: 0
})

// search
const doSearch = () => {
  router.get(route('books.index'), { search: search.value }, {
    preserveState: true,
    replace: true
  })
}

// create
const openCreate = () => {
  form.reset()
  selectedId.value = null
  isEdit.value = false
  showModal.value = true
}

// edit
const openEdit = (book) => {
  form.judul = book.judul
  form.penulis = book.penulis
  form.penerbit = book.penerbit
  form.tahun_terbit = book.tahun_terbit
  form.isbn = book.isbn
  form.stok = book.stok

  selectedId.value = book.id
  isEdit.value = true
  showModal.value = true
}

// submit
const submit = () => {
  if (isEdit.value) {
    form.put(route('books.update', selectedId.value), {
      onSuccess: () => {
        form.reset()
        showModal.value = false
      }
    })
  } else {
    form.post(route('books.store'), {
      onSuccess: () => {
        form.reset()
        showModal.value = false
      }
    })
  }
}

// delete
const destroy = (id) => {
  if (confirm('Yakin hapus buku ini?')) {
    router.delete(route('books.destroy', id))
  }
}
</script>

<template>
  <AuthenticatedLayout>

    <div class="p-6">

      <!-- HEADER -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
          📚 Data Buku
        </h1>

        <button
          v-if="can('create books')"
          @click="openCreate"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
        >
          + Tambah Buku
        </button>
      </div>

      <!-- SEARCH -->
      <div class="mb-4 flex justify-between">
        <input
          v-model="search"
          @input="doSearch"
          type="text"
          placeholder="Cari buku..."
          class="border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 rounded-lg px-4 py-2 w-64"
        />
      </div>

      <!-- TABLE -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm text-gray-700 dark:text-gray-200">
          <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
            <tr>
              <th class="p-3 text-left">Judul</th>
              <th class="p-3 text-left">Penulis</th>
              <th class="p-3 text-center">Stok</th>
              <th class="p-3 text-center">Aksi</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="book in books.data"
              :key="book.id"
              class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
            >
              <td class="p-3 font-medium">{{ book.judul }}</td>
              <td class="p-3">{{ book.penulis }}</td>

              <td class="p-3 text-center">
                  <span
                      class="px-2 py-1 rounded text-xs"
                      :class="book.stok > 0 
                        ? 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300' 
                        : 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300'"
                  >
                      {{ book.stok > 0 ? book.stok : 'Habis' }}
                  </span>
              </td>

              <td class="p-3 text-center space-x-2">

                <button
                  v-if="can('edit books')"
                  @click="openEdit(book)"
                  class="px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600"
                >
                  Edit
                </button>

                <button
                  v-if="can('delete books')"
                  @click="destroy(book.id)"
                  class="px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600"
                >
                  Hapus
                </button>

                <button
                    v-if="can('create peminjaman')"
                    @click="pinjam(book.id)"
                    :disabled="book.stok <= 0"
                    class="bg-green-600 text-white px-2 py-1 rounded disabled:opacity-50"
                >
                    Pinjam
                </button>

              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- PAGINATION -->
      <div class="flex justify-center mt-6 gap-2 flex-wrap">
        <Link
          v-for="link in books.links"
          :key="link.label"
          :href="link.url || '#'"
          v-html="link.label"
          class="px-3 py-1 border rounded text-sm border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200"
          :class="{
            'bg-blue-500 text-white': link.active,
            'text-gray-400': !link.url
          }"
        />
      </div>

      <!-- MODAL -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center"
      >
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6">

          <h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">
            {{ isEdit ? 'Edit Buku' : 'Tambah Buku' }}
          </h2>

          <form @submit.prevent="submit" class="space-y-3">

            <input v-model="form.judul" placeholder="Judul"
              class="border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-800 dark:text-white p-2 w-full rounded" />

            <input v-model="form.penulis" placeholder="Penulis"
              class="border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-800 dark:text-white p-2 w-full rounded" />

            <input v-model="form.penerbit" placeholder="Penerbit"
              class="border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-800 dark:text-white p-2 w-full rounded" />

            <input v-model="form.tahun_terbit" type="number" placeholder="Tahun"
              class="border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-800 dark:text-white p-2 w-full rounded" />

            <input v-model="form.isbn" placeholder="ISBN"
              class="border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-800 dark:text-white p-2 w-full rounded" />

            <input v-model="form.stok" type="number" placeholder="Stok"
              class="border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-800 dark:text-white p-2 w-full rounded" />

            <div class="flex justify-end gap-2 mt-4">
              <button
                type="button"
                @click="showModal = false"
                class="px-3 py-1 bg-gray-400 text-white rounded"
              >
                Batal
              </button>

              <button
                type="submit"
                :disabled="form.processing"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
              >
                {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
              </button>
            </div>

          </form>

        </div>
      </div>

    </div>

  </AuthenticatedLayout>
</template>
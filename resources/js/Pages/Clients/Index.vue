<template>
  <Head title="إدارة الموكلين" />

  <DashboardLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-0 py-6">

      <!-- ── Header ── -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
          <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">إدارة الموكلين</h1>
          <p class="text-xs font-bold text-slate-400 mt-1">سجل البيانات القانونية وسجلات التواصل الخاصة بالموكلين</p>
        </div>
        <button @click="openAddModal"
          class="inline-flex items-center gap-2 bg-[#d4af37] hover:bg-[#b8962d] text-[#1e272e] px-5 py-2.5 rounded-xl font-black text-sm shadow-lg shadow-[#d4af37]/20 transition-all">
          <i class="fas fa-user-plus"></i> إضافة موكل جديد
        </button>
      </div>

      <!-- ── Stats ── -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
        <div class="bg-white border border-slate-200 dark:border-white/5 dark:bg-white/[0.03] rounded-xl p-4 shadow-sm">
          <div class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center mb-2">
            <i class="fas fa-users text-sm text-slate-500 dark:text-slate-400"></i>
          </div>
          <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">إجمالي الموكلين</p>
          <p class="text-2xl font-black text-slate-900 dark:text-white">{{ clients.total }}</p>
        </div>
        <div class="bg-white border border-[#C0DD97] dark:border-emerald-500/20 dark:bg-emerald-500/[0.06] rounded-xl p-4 shadow-sm">
          <div class="w-9 h-9 rounded-lg bg-[#EAF3DE] dark:bg-emerald-500/10 flex items-center justify-center mb-2">
            <i class="fas fa-user-tie text-sm text-[#3B6D11] dark:text-emerald-400"></i>
          </div>
          <p class="text-[10px] font-black text-[#3B6D11] dark:text-emerald-400 uppercase tracking-widest mb-1">أفراد</p>
          <p class="text-2xl font-black text-[#27500A] dark:text-emerald-300">{{ personCount }}</p>
        </div>
        <div class="bg-white border border-[#FAC775] dark:border-amber-500/20 dark:bg-amber-500/[0.06] rounded-xl p-4 shadow-sm">
          <div class="w-9 h-9 rounded-lg bg-[#FAEEDA] dark:bg-amber-500/10 flex items-center justify-center mb-2">
            <i class="fas fa-building text-sm text-[#854F0B] dark:text-amber-400"></i>
          </div>
          <p class="text-[10px] font-black text-[#854F0B] dark:text-amber-400 uppercase tracking-widest mb-1">شركات</p>
          <p class="text-2xl font-black text-[#633806] dark:text-amber-300">{{ companyCount }}</p>
        </div>
        <div class="bg-white border border-[#B5D4F4] dark:border-blue-500/20 dark:bg-blue-500/[0.06] rounded-xl p-4 shadow-sm">
          <div class="w-9 h-9 rounded-lg bg-[#E6F1FB] dark:bg-blue-500/10 flex items-center justify-center mb-2">
            <i class="fas fa-file-alt text-sm text-[#185FA5] dark:text-blue-400"></i>
          </div>
          <p class="text-[10px] font-black text-[#185FA5] dark:text-blue-400 uppercase tracking-widest mb-1">هذه الصفحة</p>
          <p class="text-2xl font-black text-[#0C447C] dark:text-blue-300">{{ clients.data.length }}</p>
        </div>
      </div>

      <!-- ── Search ── -->
      <div class="bg-white dark:bg-[#111827] border border-slate-100 dark:border-white/5 rounded-2xl p-4 mb-5 shadow-sm">
        <div class="relative">
          <i class="fas fa-search absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
          <i v-if="isSearching" class="fas fa-spinner fa-spin absolute left-3.5 top-1/2 -translate-y-1/2 text-[#d4af37] text-sm pointer-events-none"></i>
          <button v-if="search && !isSearching" @click="clearSearch"
            class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-slate-200 dark:bg-white/10 flex items-center justify-center text-slate-500 hover:bg-red-100 hover:text-red-500 transition-all">
            <i class="fas fa-times text-[9px]"></i>
          </button>
          <input v-model="search" type="text"
            placeholder="ابحث باسم الموكل، رقم الهاتف، أو الرقم القومي..."
            class="filter-input pr-10 pl-10" />
        </div>
      </div>

      <!-- ══════════════════════════════════════
           MOBILE: كروت (hidden on md+)
      ══════════════════════════════════════ -->
      <div class="md:hidden space-y-3">

        <div v-if="!clients.data.length"
          class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 px-6 py-16 text-center">
          <i class="fas fa-users text-3xl text-slate-200 dark:text-slate-700 mb-3 block"></i>
          <p class="text-sm font-bold text-slate-400">لا يوجد موكلين مطابقين للبحث</p>
        </div>

        <div v-for="client in clients.data" :key="client.id"
          class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden shadow-sm">

          <!-- Card Header -->
          <div class="flex items-center justify-between px-4 pt-4 pb-3 border-b border-slate-50 dark:border-white/5">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-black flex-shrink-0"
                :class="client.type === 'company'
                  ? 'bg-[#FAEEDA] dark:bg-amber-500/10 text-[#854F0B] dark:text-amber-400'
                  : 'bg-[#EAF3DE] dark:bg-emerald-500/10 text-[#3B6D11] dark:text-emerald-400'">
                {{ client.name?.charAt(0) }}
              </div>
              <div>
                <p class="text-sm font-black text-slate-800 dark:text-white leading-tight">{{ client.name }}</p>
                <span :class="client.type === 'company'
                  ? 'bg-[#FAEEDA] dark:bg-amber-500/10 text-[#854F0B] dark:text-amber-400 border-[#FAC775] dark:border-amber-500/20'
                  : 'bg-[#EAF3DE] dark:bg-emerald-500/10 text-[#3B6D11] dark:text-emerald-400 border-[#C0DD97] dark:border-emerald-500/20'"
                  class="inline-block text-[9px] font-black px-2 py-0.5 rounded-full border mt-0.5">
                  {{ client.type === 'company' ? 'شركة / مؤسسة' : 'فرد' }}
                </span>
              </div>
            </div>
          </div>

          <!-- Card Body -->
          <div class="px-4 py-3 space-y-2.5">

            <div class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-300">
              <i class="fas fa-phone-alt text-[10px] text-[#d4af37] w-3.5 flex-shrink-0"></i>
              {{ client.phone }}
              <span v-if="client.phone2" class="text-slate-400 font-bold">/ {{ client.phone2 }}</span>
            </div>

            <div v-if="client.email" class="flex items-center gap-2 text-[11px] font-bold text-slate-400 font-mono">
              <i class="fas fa-envelope text-[10px] text-[#d4af37] w-3.5 flex-shrink-0"></i>
              {{ client.email }}
            </div>

            <div v-if="client.job || client.national_id" class="flex items-center gap-2">
              <i class="fas fa-briefcase text-[10px] text-[#d4af37] w-3.5 flex-shrink-0"></i>
              <span v-if="client.job" class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ client.job }}</span>
              <span v-if="client.national_id" class="text-[10px] font-mono text-slate-400">{{ client.national_id }}</span>
            </div>

            <div v-if="client.address" class="flex items-center gap-2 text-xs font-bold text-slate-500 dark:text-slate-400">
              <i class="fas fa-map-marker-alt text-[10px] text-[#d4af37] w-3.5 flex-shrink-0"></i>
              <span class="truncate">{{ client.address }}</span>
            </div>

          </div>

          <!-- Card Footer -->
          <div class="flex items-center gap-2 px-4 pb-4">
            <Link :href="`/clients/${client.id}`"
              class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-[11px] font-black border border-slate-200 dark:border-white/10 text-slate-500 hover:text-[#d4af37] hover:border-[#d4af37]/40 transition-all">
              <i class="fas fa-id-card text-[10px]"></i> الملف
            </Link>
            <button @click="openEditModal(client)"
              class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-[11px] font-black border border-slate-200 dark:border-white/10 text-slate-500 hover:text-blue-500 hover:border-blue-300 transition-all">
              <i class="fas fa-pen text-[10px]"></i> تعديل
            </button>
            <button @click="openDeleteConfirm(client)"
              class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-[11px] font-black border border-slate-200 dark:border-white/10 text-slate-500 hover:text-red-500 hover:border-red-300 transition-all">
              <i class="fas fa-trash text-[10px]"></i> حذف
            </button>
          </div>

        </div>
      </div>

      <!-- ══════════════════════════════════════
           DESKTOP: جدول (hidden on mobile)
      ══════════════════════════════════════ -->
      <div class="hidden md:block bg-white dark:bg-[#111827] rounded-2xl shadow-sm border border-slate-100 dark:border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-right">
            <thead class="bg-slate-50 dark:bg-black/20 border-b border-slate-100 dark:border-white/5">
              <tr>
                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center w-12">#</th>
                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">الموكل</th>
                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">بيانات التواصل</th>
                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">الوظيفة / الهوية</th>
                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">العنوان</th>
                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">العمليات</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-white/5">

              <tr v-for="(client, index) in clients.data" :key="client.id"
                class="hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-all group">

                <td class="px-5 py-4 text-center">
                  <span class="text-xs font-black text-slate-400">
                    {{ (clients.current_page - 1) * clients.per_page + index + 1 }}
                  </span>
                </td>

                <td class="px-5 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-black flex-shrink-0"
                      :class="client.type === 'company'
                        ? 'bg-[#FAEEDA] dark:bg-amber-500/10 text-[#854F0B] dark:text-amber-400'
                        : 'bg-[#EAF3DE] dark:bg-emerald-500/10 text-[#3B6D11] dark:text-emerald-400'">
                      {{ client.name?.charAt(0) }}
                    </div>
                    <div>
                      <p class="text-sm font-black text-slate-700 dark:text-white">{{ client.name }}</p>
                      <span :class="client.type === 'company'
                        ? 'bg-[#FAEEDA] dark:bg-amber-500/10 text-[#854F0B] dark:text-amber-400 border-[#FAC775] dark:border-amber-500/20'
                        : 'bg-[#EAF3DE] dark:bg-emerald-500/10 text-[#3B6D11] dark:text-emerald-400 border-[#C0DD97] dark:border-emerald-500/20'"
                        class="inline-block text-[9px] font-black px-2 py-0.5 rounded-full border mt-0.5">
                        {{ client.type === 'company' ? 'شركة / مؤسسة' : 'فرد' }}
                      </span>
                    </div>
                  </div>
                </td>

                <td class="px-5 py-4">
                  <div class="flex flex-col gap-1.5">
                    <div class="flex items-center gap-1.5 text-xs font-bold text-slate-600 dark:text-slate-300">
                      <i class="fas fa-phone-alt text-[10px] text-[#d4af37]"></i>
                      {{ client.phone }}
                    </div>
                    <div v-if="client.phone2" class="flex items-center gap-1.5 text-xs font-bold text-slate-500 dark:text-slate-400">
                      <i class="fas fa-phone text-[10px] text-slate-400"></i>
                      {{ client.phone2 }}
                    </div>
                    <div v-if="client.email" class="text-[10px] font-bold text-slate-400 font-mono">
                      {{ client.email }}
                    </div>
                  </div>
                </td>

                <td class="px-5 py-4">
                  <p class="text-sm font-black text-slate-600 dark:text-slate-300">{{ client.job || '—' }}</p>
                  <p class="text-[10px] font-bold text-slate-400 mt-0.5 font-mono">{{ client.national_id || 'بدون رقم قومي' }}</p>
                </td>

                <td class="px-5 py-4 max-w-[180px]">
                  <div class="flex items-center gap-1.5 text-xs font-bold text-slate-500 dark:text-slate-400">
                    <i class="fas fa-map-marker-alt text-[10px] text-[#d4af37] flex-shrink-0"></i>
                    <span class="truncate" :title="client.address">{{ client.address || 'غير مسجل' }}</span>
                  </div>
                </td>

                <td class="px-5 py-4">
                  <div class="flex items-center justify-center gap-1.5">
                    <Link :href="`/clients/${client.id}`"
                      class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-[#d4af37] hover:border-[#d4af37]/30 transition-all"
                      title="عرض الملف">
                      <i class="fas fa-id-card text-xs"></i>
                    </Link>
                    <button @click="openEditModal(client)"
                      class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-blue-500 hover:border-blue-300 dark:hover:border-blue-500/40 transition-all"
                      title="تعديل">
                      <i class="fas fa-pen text-xs"></i>
                    </button>
                    <button @click="openDeleteConfirm(client)"
                      class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-300 dark:hover:border-red-500/40 transition-all"
                      title="حذف">
                      <i class="fas fa-trash text-xs"></i>
                    </button>
                  </div>
                </td>

              </tr>

              <tr v-if="!clients.data.length">
                <td colspan="6" class="px-6 py-16 text-center">
                  <i class="fas fa-users text-3xl text-slate-200 dark:text-slate-700 mb-3 block"></i>
                  <p class="text-sm font-bold text-slate-400">لا يوجد موكلين مطابقين للبحث</p>
                </td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>

      <!-- ── Pagination (shared) ── -->
      <div v-if="clients.last_page > 1"
        class="mt-4 px-5 py-4 bg-white dark:bg-[#111827] border border-slate-100 dark:border-white/5 rounded-2xl flex items-center justify-between">
        <p class="text-xs font-bold text-slate-400">
          عرض {{ clients.from }}–{{ clients.to }} من أصل
          <span class="font-black text-slate-600 dark:text-slate-300">{{ clients.total }}</span> موكل
        </p>
        <div class="flex items-center gap-1.5">
          <Link v-if="clients.prev_page_url"
            :href="clients.prev_page_url + searchSuffix" preserve-state
            class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 hover:text-[#d4af37] hover:border-[#d4af37]/30 transition-all text-xs">
            <i class="fas fa-chevron-right"></i>
          </Link>
          <template v-for="page in paginationPages" :key="page">
            <span v-if="page === '...'" class="px-1 text-xs text-slate-400 font-black">...</span>
            <Link v-else
              :href="clients.path + '?page=' + page + searchSuffix" preserve-state
              :class="page === clients.current_page
                ? 'bg-[#d4af37] text-[#1e272e] border-[#d4af37]'
                : 'border-slate-200 dark:border-white/10 text-slate-500 hover:text-[#d4af37] hover:border-[#d4af37]/30'"
              class="w-8 h-8 rounded-lg border flex items-center justify-center text-xs font-black transition-all">
              {{ page }}
            </Link>
          </template>
          <Link v-if="clients.next_page_url"
            :href="clients.next_page_url + searchSuffix" preserve-state
            class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 hover:text-[#d4af37] hover:border-[#d4af37]/30 transition-all text-xs">
            <i class="fas fa-chevron-left"></i>
          </Link>
        </div>
      </div>

    </div>

    <!-- ===== Modal: إضافة / تعديل ===== -->
    <div v-if="modals.form" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
      <div class="bg-white dark:bg-[#111827] w-full max-w-lg rounded-2xl shadow-2xl border border-slate-200 dark:border-white/10 overflow-hidden max-h-[90vh] flex flex-col">

        <div class="px-6 py-5 border-b border-slate-100 dark:border-white/5 flex justify-between items-center bg-slate-50/50 dark:bg-black/20 flex-shrink-0">
          <div>
            <h3 class="font-black text-base text-slate-800 dark:text-white">
              {{ isEdit ? 'تعديل بيانات الموكل' : 'إضافة موكل جديد' }}
            </h3>
            <p v-if="isEdit" class="text-[10px] font-bold text-slate-400 mt-0.5">{{ form.name }}</p>
          </div>
          <button @click="modals.form = false"
            class="w-7 h-7 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-200 transition-all">
            <i class="fas fa-times text-xs"></i>
          </button>
        </div>

        <form @submit.prevent="submitForm" class="p-6 overflow-y-auto">
          <div v-if="Object.keys(form.errors).length"
            class="mb-4 p-3 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-xl text-red-600 dark:text-red-400 text-xs font-bold space-y-1">
            <div v-for="(error, key) in form.errors" :key="key">• {{ error }}</div>
          </div>

          <div class="grid grid-cols-2 gap-4 mb-5">
            <div class="col-span-1">
              <label class="form-label">الاسم بالكامل *</label>
              <input v-model="form.name" type="text" class="form-input" placeholder="الاسم الرباعي" required>
            </div>
            <div class="col-span-1">
              <label class="form-label">نوع الموكل</label>
              <select v-model="form.type" class="form-input appearance-none">
                <option disabled value="">اختيار نوع الموكل</option>
                <option value="person">فرد</option>
                <option value="company">شركة / مؤسسة</option>
              </select>
            </div>
            <div class="col-span-1">
              <label class="form-label">الهاتف 1 *</label>
              <input v-model="form.phone" type="text" class="form-input" placeholder="01xxxxxxxxx" required>
            </div>
            <div class="col-span-1">
              <label class="form-label">الهاتف 2</label>
              <input v-model="form.phone2" type="text" class="form-input" placeholder="01xxxxxxxxx">
            </div>
            <div class="col-span-1">
              <label class="form-label">الرقم القومي</label>
              <input v-model="form.national_id" type="text" class="form-input" placeholder="14 رقم">
            </div>
            <div class="col-span-1">
              <label class="form-label">البريد الإلكتروني</label>
              <input v-model="form.email" type="email" class="form-input" placeholder="mail@example.com">
            </div>
            <div class="col-span-1">
              <label class="form-label">الوظيفة</label>
              <input v-model="form.job" type="text" class="form-input" placeholder="المسمى الوظيفي">
            </div>
            <div class="col-span-1">
              <label class="form-label">جهة العمل</label>
              <input v-model="form.company" type="text" class="form-input" placeholder="اسم الشركة / المؤسسة">
            </div>
            <div class="col-span-2">
              <label class="form-label">العنوان</label>
              <input v-model="form.address" type="text" class="form-input" placeholder="المحافظة، المركز، الشارع">
            </div>
            <div class="col-span-2">
              <label class="form-label">ملاحظات إضافية</label>
              <textarea v-model="form.notes" rows="2" class="form-input resize-none" placeholder="أي تفاصيل قانونية أخرى..."></textarea>
            </div>
          </div>

          <div class="flex gap-3">
            <button type="button" @click="modals.form = false"
              class="flex-1 py-3 text-xs font-black text-slate-500 bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition-all border border-slate-200 dark:border-white/10">
              إلغاء
            </button>
            <button type="submit" :disabled="form.processing"
              class="flex-[2] py-3 bg-[#d4af37] text-[#1e272e] font-black rounded-xl hover:bg-[#b8962d] transition-all flex items-center justify-center gap-2 disabled:opacity-60 shadow-lg shadow-[#d4af37]/20">
              <i v-if="form.processing" class="fas fa-spinner fa-spin text-xs"></i>
              <i v-else class="fas fa-check-circle text-xs"></i>
              {{ isEdit ? 'تحديث البيانات' : 'حفظ الموكل' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ===== Modal: تأكيد الحذف ===== -->
    <div v-if="modals.delete" class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
      <div class="bg-white dark:bg-[#111827] w-full max-w-sm rounded-2xl shadow-2xl border border-slate-200 dark:border-white/10 overflow-hidden">
        <div class="px-6 pt-8 pb-5 text-center">
          <div class="w-16 h-16 rounded-full bg-red-50 dark:bg-red-500/10 border-4 border-red-100 dark:border-red-500/20 flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-trash-alt text-2xl text-red-500"></i>
          </div>
          <h3 class="text-base font-black text-slate-800 dark:text-white mb-2">تأكيد الحذف</h3>
          <p class="text-xs font-bold text-slate-500 dark:text-slate-400 leading-relaxed">
            هل أنت متأكد من حذف الموكل
            <span class="font-black text-slate-700 dark:text-slate-200">{{ selectedClient?.name }}</span>؟
          </p>
          <p class="text-[10px] font-black text-red-500 mt-2">سيتم حذف جميع بياناته. لا يمكن التراجع.</p>
        </div>
        <div class="flex gap-3 px-6 pb-6">
          <button @click="modals.delete = false"
            class="flex-1 py-2.5 text-xs font-black text-slate-500 bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition-all border border-slate-200 dark:border-white/10">
            إلغاء
          </button>
          <button @click="confirmDelete" :disabled="deleteProcessing"
            class="flex-1 py-2.5 text-xs font-black text-white bg-red-500 rounded-xl hover:bg-red-600 transition-all flex items-center justify-center gap-2 disabled:opacity-60">
            <i v-if="deleteProcessing" class="fas fa-spinner fa-spin text-xs"></i>
            <i v-else class="fas fa-trash text-xs"></i>
            نعم، احذف
          </button>
        </div>
      </div>
    </div>

  </DashboardLayout>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { toast } from 'vue-sonner'

// ── Props ──────────────────────────────────────────
const props = defineProps({
  clients: Object,
  filters: Object,
})

// ── State ──────────────────────────────────────────
const isEdit           = ref(false)
const selectedClient   = ref(null)
const deleteProcessing = ref(false)
const isSearching      = ref(false)
const modals           = reactive({ form: false, delete: false })

// ── Search with debounce ───────────────────────────
const search = ref(props.filters?.search || '')
let searchTimeout = null

watch(search, (val) => {
  clearTimeout(searchTimeout)
  isSearching.value = true
  searchTimeout = setTimeout(() => {
    router.get('clients', { search: val || undefined }, {
      preserveState: true,
      replace: true,
      onFinish: () => { isSearching.value = false },
    })
  }, 450)
})

const clearSearch = () => {
  search.value = ''
  isSearching.value = false
  clearTimeout(searchTimeout)
  router.get('clients', {}, { preserveState: false, replace: true })
}

const searchSuffix = computed(() =>
  search.value ? '&search=' + encodeURIComponent(search.value) : ''
)

// ── Stats ──────────────────────────────────────────
const personCount  = computed(() => props.clients.data.filter(c => c.type === 'person').length)
const companyCount = computed(() => props.clients.data.filter(c => c.type === 'company').length)

// ── Pagination ─────────────────────────────────────
const paginationPages = computed(() => {
  const current = props.clients.current_page
  const last    = props.clients.last_page
  const pages   = []
  if (last <= 7) {
    for (let i = 1; i <= last; i++) pages.push(i)
  } else {
    pages.push(1)
    if (current > 3) pages.push('...')
    for (let i = Math.max(2, current - 1); i <= Math.min(last - 1, current + 1); i++) pages.push(i)
    if (current < last - 2) pages.push('...')
    pages.push(last)
  }
  return pages
})

// ── Form ───────────────────────────────────────────
const form = useForm({
  id: null, name: '', phone: '', phone2: '', email: '',
  national_id: '', job: '', company: '', address: '', type: '', notes: '',
})

const openAddModal = () => {
  isEdit.value = false
  form.reset()
  form.clearErrors()
  modals.form = true
}

const openEditModal = (client) => {
  isEdit.value = true
  Object.assign(form, {
    id: client.id, name: client.name || '', phone: client.phone || '',
    phone2: client.phone2 || '', email: client.email || '',
    national_id: client.national_id || '', job: client.job || '',
    company: client.company || '', address: client.address || '',
    type: client.type || '', notes: client.notes || '',
  })
  form.clearErrors()
  modals.form = true
}

const submitForm = () => {
  const opts = {
    onSuccess: () => {
      modals.form = false
      toast.success(isEdit.value ? 'تم تعديل بيانات الموكل بنجاح' : 'تم إضافة الموكل بنجاح')
      if (!isEdit.value) form.reset()
    },
    onError: () => toast.error('حدث خطأ، يرجى مراجعة البيانات'),
  }
  isEdit.value ? form.put(`clients/${form.id}`, opts) : form.post('clients/', opts)
}

const openDeleteConfirm = (client) => {
  selectedClient.value   = client
  deleteProcessing.value = false
  modals.delete = true
}

const confirmDelete = () => {
  deleteProcessing.value = true
  router.delete(`clients/${selectedClient.value.id}`, {
    onSuccess: () => {
      modals.delete = false
      deleteProcessing.value = false
      toast.success('تم حذف الموكل بنجاح')
    },
    onError: () => {
      deleteProcessing.value = false
      toast.error('حدث خطأ أثناء الحذف')
    },
  })
}
</script>

<style scoped>
.filter-input {
  background-color: #f8fafc;
  border: 1.5px solid #e2e8f0;
  border-radius: 0.625rem;
  padding: 0.625rem 0.875rem;
  font-size: 0.875rem;
  font-weight: 700;
  outline: none;
  transition: all 0.2s;
  color: #1e293b;
  width: 100%;
}
.filter-input:focus {
  border-color: #d4af37;
  background-color: #fff;
  box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
}
:where(.dark) .filter-input {
  background-color: rgba(0, 0, 0, 0.2);
  border-color: rgba(255, 255, 255, 0.08);
  color: white;
}
:where(.dark) .filter-input:focus { border-color: #d4af37; }

.form-label {
  display: block;
  font-size: 10px;
  font-weight: 900;
  color: #94a3b8;
  text-transform: uppercase;
  margin-bottom: 0.375rem;
  letter-spacing: 0.1em;
}
.form-input {
  width: 100%;
  background-color: #f8fafc;
  border: 1.5px solid #e2e8f0;
  border-radius: 0.625rem;
  padding: 0.75rem 0.875rem;
  font-size: 0.875rem;
  font-weight: 700;
  outline: none;
  transition: all 0.2s;
  color: #1e293b;
}
.form-input:focus {
  border-color: #d4af37;
  background-color: #fff;
  box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
}
:where(.dark) .form-input {
  background-color: rgba(0, 0, 0, 0.2);
  border-color: rgba(255, 255, 255, 0.08);
  color: white;
}
:where(.dark) .form-input:focus {
  border-color: #d4af37;
  background-color: rgba(0, 0, 0, 0.3);
}
</style>

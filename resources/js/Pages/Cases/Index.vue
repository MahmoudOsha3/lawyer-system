<template>
  <Head title="إدارة القضايا" />

  <DashboardLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-0 pb-10 pt-6">

      <!-- ── Header ── -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
          <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">إدارة القضايا</h1>
          <p class="text-xs font-bold text-slate-400 mt-1">تتبع ملفات القضايا والتحصيل المالي الخاص بمكتبكم</p>
        </div>
        <button @click="openAddModal"
          class="inline-flex items-center gap-2 bg-[#d4af37] hover:bg-[#b8962d] text-[#1e272e] px-5 py-2.5 rounded-xl font-black text-sm shadow-lg shadow-[#d4af37]/20 transition-all">
          <i class="fas fa-plus-circle"></i> إضافة قضية جديدة
        </button>
      </div>

      <!-- ── Stats ── -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
        <div class="bg-slate-100 dark:bg-white/[0.03] border border-slate-200 dark:border-white/5 rounded-xl p-4">
          <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">إجمالي القضايا</p>
          <p class="text-2xl font-black text-slate-900 dark:text-white">{{ cases.total }}</p>
        </div>
        <div class="bg-[#EAF3DE] dark:bg-emerald-500/[0.06] border border-[#C0DD97] dark:border-emerald-500/20 rounded-xl p-4">
          <p class="text-[10px] font-black text-[#3B6D11] dark:text-emerald-400 uppercase tracking-widest mb-1.5">قضايا نشطة</p>
          <p class="text-2xl font-black text-[#27500A] dark:text-emerald-300">{{ openCount }}</p>
        </div>
        <div class="bg-[#FAEEDA] dark:bg-amber-500/[0.06] border border-[#FAC775] dark:border-amber-500/20 rounded-xl p-4">
          <p class="text-[10px] font-black text-[#854F0B] dark:text-amber-400 uppercase tracking-widest mb-1.5">قيد الإعداد</p>
          <p class="text-2xl font-black text-[#633806] dark:text-amber-300">{{ pendingCount }}</p>
        </div>
        <div class="bg-[#FCEBEB] dark:bg-red-500/[0.06] border border-[#F7C1C1] dark:border-red-500/20 rounded-xl p-4">
          <p class="text-[10px] font-black text-[#A32D2D] dark:text-red-400 uppercase tracking-widest mb-1.5">مغلقة</p>
          <p class="text-2xl font-black text-[#791F1F] dark:text-red-300">{{ closedCount }}</p>
        </div>
      </div>

      <!-- ── Unified Search ── -->
      <div class="bg-white dark:bg-[#111827] border border-slate-100 dark:border-white/5 rounded-2xl p-4 mb-5">
        <div class="relative">
          <!-- أيقونة البحث -->
          <i class="fas fa-search absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>

          <!-- مؤشر التحميل -->
          <i v-if="isSearching"
            class="fas fa-spinner fa-spin absolute left-3.5 top-1/2 -translate-y-1/2 text-[#d4af37] text-sm pointer-events-none"></i>

          <!-- زر المسح -->
          <button v-if="searchQuery && !isSearching"
            @click="clearSearch"
            class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-slate-200 dark:bg-white/10 flex items-center justify-center text-slate-500 hover:bg-red-100 hover:text-red-500 transition-all">
            <i class="fas fa-times text-[9px]"></i>
          </button>

          <input
            v-model="searchQuery"
            @input="onSearchInput"
            type="text"
            placeholder="ابحث باسم الموكل أو رقم القضية..."
            class="filter-input pr-10 pl-10"
          />
        </div>

        <!-- تلميح -->
        <p class="text-[10px] font-bold text-slate-400 mt-2 flex items-center gap-1.5">
          <i class="fas fa-info-circle text-[9px]"></i>
          يمكنك البحث باسم الموكل أو رقم القضية — البحث تلقائي عند الكتابة
        </p>
      </div>

      <!-- ══════════════════════════════════════
           MOBILE: بطاقات (hidden on md+)
      ══════════════════════════════════════ -->
      <div class="md:hidden space-y-3">

        <!-- Empty State Mobile -->
        <div v-if="!cases.data.length"
          class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 px-6 py-16 text-center">
          <i class="fas fa-folder-open text-3xl text-slate-200 dark:text-slate-700 mb-3 block"></i>
          <p class="text-sm font-bold text-slate-400">لا توجد قضايا مطابقة للبحث</p>
        </div>

        <div
          v-for="legalCase in cases.data"
          :key="legalCase.id"
          class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden shadow-sm"
        >
          <!-- Card Header -->
          <div class="flex items-center justify-between px-4 pt-4 pb-3 border-b border-slate-50 dark:border-white/5">
            <div class="flex items-center gap-2.5">
              <!-- أفاتار الموكل -->
              <div class="w-9 h-9 rounded-full bg-[#FAEEDA] dark:bg-amber-500/10 flex items-center justify-center text-sm font-black text-[#854F0B] dark:text-amber-400 flex-shrink-0">
                {{ legalCase.client?.name?.charAt(0) }}
              </div>
              <div>
                <p class="text-sm font-black text-slate-800 dark:text-white leading-tight">{{ legalCase.client?.name }}</p>
                <p class="text-[10px] font-bold text-slate-400 mt-0.5">#{{ legalCase.case_number }}</p>
              </div>
            </div>
            <!-- Badge الحالة -->
            <span :class="statusPill(legalCase.status)"
              class="inline-flex items-center gap-1.5 text-[10px] font-black px-2.5 py-1 rounded-full border">
              <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
              {{ translateStatus(legalCase.status) }}
            </span>
          </div>

          <!-- Card Body -->
          <div class="px-4 py-3 grid grid-cols-2 gap-x-4 gap-y-2.5">

            <div>
              <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">نوع القضية</p>
              <span class="inline-block text-[11px] font-black px-2 py-0.5 rounded-full bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400">
                {{ legalCase.case_type }}
              </span>
            </div>

            <div>
              <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">المحكمة</p>
              <p class="text-xs font-bold text-slate-700 dark:text-slate-300 truncate">{{ legalCase.court }}</p>
            </div>

            <div class="col-span-2">
              <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">الجلسة القادمة</p>
              <span v-if="legalCase.next_session"
                class="inline-flex items-center gap-1.5 text-[10px] font-black bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-500/20 px-2.5 py-1 rounded-lg">
                <i class="far fa-calendar-alt text-[10px]"></i>
                {{ formatDate(legalCase.next_session) }}
              </span>
              <span v-else class="text-[10px] font-bold text-slate-400 italic">لم يحدد</span>
            </div>

          </div>

          <!-- Card Footer: أزرار العمليات -->
          <div class="flex items-center gap-2 px-4 pb-4">
            <Link :href="`/cases/${legalCase.id}`"
              class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-[11px] font-black border border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 hover:text-[#d4af37] hover:border-[#d4af37]/40 transition-all">
              <i class="fas fa-external-link-alt text-[10px]"></i> تفاصيل
            </Link>
            <button @click="openEditModal(legalCase)"
              class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-[11px] font-black border border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 hover:text-blue-500 hover:border-blue-300 dark:hover:border-blue-500/40 transition-all">
              <i class="fas fa-pen text-[10px]"></i> تعديل
            </button>
            <button @click="openDeleteConfirm(legalCase)"
              class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-[11px] font-black border border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 hover:text-red-500 hover:border-red-300 dark:hover:border-red-500/40 transition-all">
              <i class="fas fa-trash text-[10px]"></i> حذف
            </button>
          </div>

        </div>
      </div>

      <!-- ══════════════════════════════════════
           DESKTOP: جدول (hidden on mobile)
      ══════════════════════════════════════ -->
      <div class="hidden md:block bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-right">
            <thead class="bg-slate-50 dark:bg-black/20 border-b border-slate-100 dark:border-white/5">
              <tr>
                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">رقم القضية</th>
                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">الموكل</th>
                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">نوع القضية</th>
                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">الجلسة القادمة</th>
                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">المحكمة</th>
                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">الحالة</th>
                <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">العمليات</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-white/5">

              <tr v-for="legalCase in cases.data" :key="legalCase.id"
                class="hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-all">

                <td class="px-5 py-4">
                  <p class="text-sm font-black text-slate-700 dark:text-white">{{ legalCase.case_number }}</p>
                </td>

                <td class="px-5 py-4">
                  <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-[#FAEEDA] dark:bg-amber-500/10 flex items-center justify-center text-xs font-black text-[#854F0B] dark:text-amber-400 flex-shrink-0">
                      {{ legalCase.client?.name?.charAt(0) }}
                    </div>
                    <span class="text-sm font-black text-slate-700 dark:text-slate-200">{{ legalCase.client?.name }}</span>
                  </div>
                </td>

                <td class="px-5 py-4">
                  <span class="inline-block text-[10px] font-black px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400">
                    {{ legalCase.case_type }}
                  </span>
                </td>

                <td class="px-5 py-4">
                  <span v-if="legalCase.next_session"
                    class="inline-flex items-center gap-1.5 text-[10px] font-black bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-500/20 px-2.5 py-1 rounded-lg">
                    <i class="far fa-calendar-alt text-[10px]"></i>
                    {{ formatDate(legalCase.next_session) }}
                  </span>
                  <span v-else class="text-[10px] font-bold text-slate-400 italic">لم يحدد</span>
                </td>

                <td class="px-5 py-4">
                  <span class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ legalCase.court }}</span>
                </td>

                <td class="px-5 py-4">
                  <span :class="statusPill(legalCase.status)"
                    class="inline-flex items-center gap-1.5 text-[10px] font-black px-2.5 py-1 rounded-full border">
                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                    {{ translateStatus(legalCase.status) }}
                  </span>
                </td>

                <td class="px-5 py-4">
                  <div class="flex items-center justify-center gap-1.5">
                    <Link :href="`/cases/${legalCase.id}`"
                      class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-[#d4af37] hover:border-[#d4af37]/30 transition-all"
                      title="عرض تفاصيل القضية">
                      <i class="fas fa-external-link-alt text-xs"></i>
                    </Link>
                    <button @click="openEditModal(legalCase)"
                      class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-blue-500 hover:border-blue-300 dark:hover:border-blue-500/40 transition-all"
                      title="تعديل">
                      <i class="fas fa-pen text-xs"></i>
                    </button>
                    <button @click="openDeleteConfirm(legalCase)"
                      class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-300 dark:hover:border-red-500/40 transition-all"
                      title="حذف">
                      <i class="fas fa-trash text-xs"></i>
                    </button>
                  </div>
                </td>

              </tr>

              <!-- Empty State -->
              <tr v-if="!cases.data.length">
                <td colspan="7" class="px-6 py-16 text-center">
                  <i class="fas fa-folder-open text-3xl text-slate-200 dark:text-slate-700 mb-3 block"></i>
                  <p class="text-sm font-bold text-slate-400">لا توجد قضايا مطابقة للبحث</p>
                </td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>

      <!-- ── Pagination (shared) ── -->
      <div v-if="cases.last_page > 1"
        class="mt-4 px-5 py-4 bg-white dark:bg-[#111827] border border-slate-100 dark:border-white/5 rounded-2xl flex items-center justify-between">
        <p class="text-xs font-bold text-slate-400">
          عرض {{ cases.from }}–{{ cases.to }} من أصل
          <span class="font-black text-slate-600 dark:text-slate-300">{{ cases.total }}</span> قضية
        </p>
        <div class="flex items-center gap-1.5">
          <Link
            v-if="cases.prev_page_url"
            :href="cases.prev_page_url"
            preserve-state
            class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 hover:text-[#d4af37] hover:border-[#d4af37]/30 transition-all text-xs">
            <i class="fas fa-chevron-right"></i>
          </Link>

          <template v-for="page in paginationPages" :key="page">
            <span v-if="page === '...'" class="px-1 text-xs text-slate-400 font-black">...</span>
            <Link
              v-else
              :href="cases.path + '?page=' + page + pageQuerySuffix"
              preserve-state
              :class="page === cases.current_page
                ? 'bg-[#d4af37] text-[#1e272e] border-[#d4af37]'
                : 'border-slate-200 dark:border-white/10 text-slate-500 hover:text-[#d4af37] hover:border-[#d4af37]/30'"
              class="w-8 h-8 rounded-lg border flex items-center justify-center text-xs font-black transition-all">
              {{ page }}
            </Link>
          </template>

          <Link
            v-if="cases.next_page_url"
            :href="cases.next_page_url"
            preserve-state
            class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 hover:text-[#d4af37] hover:border-[#d4af37]/30 transition-all text-xs">
            <i class="fas fa-chevron-left"></i>
          </Link>
        </div>
      </div>

    </div>

    <!-- ===== Modal: إضافة / تعديل قضية ===== -->
    <div v-if="modals.form" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
      <div class="bg-white dark:bg-[#111827] w-full max-w-2xl rounded-2xl shadow-2xl border border-slate-200 dark:border-white/10 overflow-hidden max-h-[90vh] flex flex-col">

        <div class="px-6 py-5 border-b border-slate-100 dark:border-white/5 flex justify-between items-center bg-slate-50/50 dark:bg-black/20 flex-shrink-0">
          <div>
            <h3 class="font-black text-base text-slate-800 dark:text-white">
              {{ isEdit ? 'تعديل بيانات القضية' : 'إضافة قضية جديدة' }}
            </h3>
            <p v-if="isEdit" class="text-[10px] font-bold text-slate-400 mt-0.5">{{ caseForm.case_number }}</p>
          </div>
          <button @click="modals.form = false"
            class="w-7 h-7 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-200 transition-all">
            <i class="fas fa-times text-xs"></i>
          </button>
        </div>

        <form @submit.prevent="submitForm" class="p-6 overflow-y-auto">
          <div class="grid grid-cols-2 gap-4 mb-5">

            <!-- اختيار الموكل -->
            <div class="col-span-2" v-if="!isEdit">
              <label class="form-label">الموكل *</label>
              <div class="relative">
                <i class="fas fa-user absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input
                  v-model="clientSearch"
                  @input="onClientSearch"
                  @focus="showClientDropdown = true"
                  type="text"
                  :placeholder="selectedClient ? selectedClient.name : 'ابحث عن اسم الموكل...'"
                  :class="selectedClient ? 'border-[#d4af37]' : ''"
                  class="form-input pr-9"
                  autocomplete="off"
                />
                <div v-if="showClientDropdown && filteredClients.length"
                  class="absolute top-full right-0 left-0 z-50 mt-1 bg-white dark:bg-[#111827] border border-slate-200 dark:border-white/10 rounded-xl shadow-xl overflow-hidden max-h-48 overflow-y-auto">
                  <button
                    v-for="client in filteredClients"
                    :key="client.id"
                    type="button"
                    @click="selectClient(client)"
                    class="w-full text-right px-4 py-2.5 text-sm font-bold text-slate-700 dark:text-slate-200 hover:bg-[#FAEEDA] dark:hover:bg-amber-500/10 hover:text-[#854F0B] dark:hover:text-amber-400 transition-colors flex items-center gap-2.5">
                    <span class="w-6 h-6 rounded-full bg-[#FAEEDA] dark:bg-amber-500/10 flex items-center justify-center text-[10px] font-black text-[#854F0B] dark:text-amber-400 flex-shrink-0">
                      {{ client.name.charAt(0) }}
                    </span>
                    {{ client.name }}
                  </button>
                </div>
                <p v-if="selectedClient" class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 mt-1 flex items-center gap-1">
                  <i class="fas fa-check-circle text-[9px]"></i>
                  تم اختيار: {{ selectedClient.name }}
                </p>
                <p v-if="caseForm.errors.client_id" class="text-[10px] font-bold text-red-500 mt-1">
                  {{ caseForm.errors.client_id }}
                </p>
              </div>
            </div>

            <div class="col-span-1">
              <label class="form-label">رقم القضية *</label>
              <input v-model="caseForm.case_number" type="text" class="form-input" placeholder="مثال: 1234 لسنة 2026" required>
              <p v-if="caseForm.errors.case_number" class="err-msg">{{ caseForm.errors.case_number }}</p>
            </div>

            <div class="col-span-1">
              <label class="form-label">نوع القضية *</label>
              <select v-model="caseForm.case_type" class="form-input appearance-none" required>
                <option disabled value="">-- اختيار نوع القضية --</option>
                <option value="جنائي">جنائي</option>
                <option value="مدني">مدني</option>
                <option value="أسرة">أسرة</option>
                <option value="تجاري">تجاري</option>
                <option value="إداري">إداري</option>
                <option value="جنح">جنح</option>
                <option value="جنايات">جنايات</option>
                <option value="أحوال شخصية">أحوال شخصية</option>
                <option value="عمال">عمال</option>
                <option value="قضايا شركات">قضايا شركات</option>
                <option value="قضايا عقارية">قضايا عقارية</option>
              </select>
              <p v-if="caseForm.errors.case_type" class="err-msg">{{ caseForm.errors.case_type }}</p>
            </div>

            <div class="col-span-1">
              <label class="form-label">المحكمة *</label>
              <input v-model="caseForm.court" type="text" class="form-input" placeholder="اسم المحكمة والدائرة" required>
              <p v-if="caseForm.errors.court" class="err-msg">{{ caseForm.errors.court }}</p>
            </div>

            <div class="col-span-1">
              <label class="form-label">الخصم *</label>
              <input v-model="caseForm.opponent" type="text" class="form-input" placeholder="اسم الخصم" required>
              <p v-if="caseForm.errors.opponent" class="err-msg">{{ caseForm.errors.opponent }}</p>
            </div>

            <div class="col-span-1">
              <label class="form-label">تاريخ البداية *</label>
              <input v-model="caseForm.start_date" type="date" class="form-input" required>
              <p v-if="caseForm.errors.start_date" class="err-msg">{{ caseForm.errors.start_date }}</p>
            </div>

            <div class="col-span-1">
              <label class="form-label">الجلسة القادمة *</label>
              <input v-model="caseForm.next_session" type="date" class="form-input" required>
              <p v-if="caseForm.errors.next_session" class="err-msg">{{ caseForm.errors.next_session }}</p>
            </div>

            <div class="col-span-1">
              <label class="form-label">إجمالي الأتعاب (ج.م) *</label>
              <input v-model="caseForm.case_price" type="number" step="0.01" class="form-input" placeholder="0.00" required>
              <p v-if="caseForm.errors.case_price" class="err-msg">{{ caseForm.errors.case_price }}</p>
            </div>

            <div class="col-span-1">
              <label class="form-label">حالة القضية *</label>
              <select v-model="caseForm.status" class="form-input" required>
                <option value="pending">قيد الإعداد</option>
                <option value="open">نشطة</option>
                <option value="closed">مغلقة</option>
              </select>
              <p v-if="caseForm.errors.status" class="err-msg">{{ caseForm.errors.status }}</p>
            </div>

            <div class="col-span-2">
              <label class="form-label">وصف القضية / ملاحظات *</label>
              <textarea v-model="caseForm.description" rows="3" class="form-input resize-none" placeholder="اكتب ملخص القضية هنا..." required></textarea>
              <p v-if="caseForm.errors.description" class="err-msg">{{ caseForm.errors.description }}</p>
            </div>

          </div>

          <div class="flex gap-3">
            <button type="button" @click="modals.form = false"
              class="flex-1 py-3 text-xs font-black text-slate-500 bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition-all border border-slate-200 dark:border-white/10">
              إلغاء
            </button>
            <button type="submit" :disabled="caseForm.processing"
              class="flex-[2] py-3 bg-[#d4af37] text-[#1e272e] font-black rounded-xl hover:bg-[#b8962d] transition-all flex items-center justify-center gap-2 disabled:opacity-60 shadow-lg shadow-[#d4af37]/20">
              <i v-if="caseForm.processing" class="fas fa-spinner fa-spin text-xs"></i>
              <i v-else class="fas fa-save text-xs"></i>
              {{ isEdit ? 'حفظ التعديلات' : 'حفظ بيانات القضية' }}
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
            هل أنت متأكد من حذف القضية
            <span class="font-black text-slate-700 dark:text-slate-200">{{ selectedCase?.case_number }}</span>
            للموكل
            <span class="font-black text-slate-700 dark:text-slate-200">{{ selectedCase?.client?.name }}</span>؟
          </p>
          <p class="text-[10px] font-black text-red-500 mt-2">لا يمكن التراجع عن هذا الإجراء.</p>
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
import { ref, reactive, computed, onMounted, onBeforeUnmount } from 'vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { toast } from 'vue-sonner'

// ── Props ──────────────────────────────────────────
const props = defineProps({
  cases:   Object, // Laravel Paginated
  filters: Object, // { name, case_number }
  clients: Array,  // [ { id, name } ]
})

// ── State ──────────────────────────────────────────
const isEdit           = ref(false)
const selectedCase     = ref(null)
const deleteProcessing = ref(false)
const modals           = reactive({ form: false, delete: false })

// ── Unified Search with Debounce ───────────────────
// يبحث في اسم الموكل أو رقم القضية بنفس الوقت
const searchQuery  = ref(props.filters?.name || props.filters?.case_number || '')
const isSearching  = ref(false)
let   searchTimer  = null

const onSearchInput = () => {
  clearTimeout(searchTimer)
  isSearching.value = true
  searchTimer = setTimeout(() => {
    applySearch()
  }, 500) // 500ms debounce
}

const applySearch = () => {
  const q = searchQuery.value.trim()
  // نحدد هل البحث برقم (أرقام) أم باسم
  const isNumeric = /^\d/.test(q)

  router.get('cases',
    {
      name:        (!isNumeric && q) ? q : undefined,
      case_number: (isNumeric  && q) ? q : undefined,
    },
    {
      preserveState: true,
      replace: true,
      onFinish: () => { isSearching.value = false },
    }
  )
}

const clearSearch = () => {
  searchQuery.value = ''
  isSearching.value = false
  clearTimeout(searchTimer)
  router.get(route('cases.index'), {}, { preserveState: false, replace: true })
}

// suffix للـ pagination يحافظ على الفلاتر
const pageQuerySuffix = computed(() => {
  const q = searchQuery.value.trim()
  if (!q) return ''
  const isNumeric = /^\d/.test(q)
  if (isNumeric) return `&case_number=${encodeURIComponent(q)}`
  return `&name=${encodeURIComponent(q)}`
})

// ── Client Dropdown Search ─────────────────────────
const clientSearch       = ref('')
const showClientDropdown = ref(false)
const selectedClient     = ref(null)

const filteredClients = computed(() => {
  if (!clientSearch.value.trim()) return props.clients.slice(0, 10)
  const q = clientSearch.value.toLowerCase().trim()
  return props.clients.filter(c => c.name.toLowerCase().includes(q)).slice(0, 10)
})

const onClientSearch = () => {
  showClientDropdown.value = true
  selectedClient.value     = null
  caseForm.client_id       = ''
}

const selectClient = (client) => {
  selectedClient.value     = client
  clientSearch.value       = client.name
  caseForm.client_id       = client.id
  showClientDropdown.value = false
}

const closeDropdown = (e) => {
  if (!e.target.closest('.relative')) showClientDropdown.value = false
}
onMounted(()    => document.addEventListener('click', closeDropdown))
onBeforeUnmount(() => {
  document.removeEventListener('click', closeDropdown)
  clearTimeout(searchTimer)
})

// ── Stats ──────────────────────────────────────────
const openCount    = computed(() => props.cases.data.filter(c => c.status === 'open').length)
const pendingCount = computed(() => props.cases.data.filter(c => c.status === 'pending').length)
const closedCount  = computed(() => props.cases.data.filter(c => c.status === 'closed').length)

// ── Pagination Pages ───────────────────────────────
const paginationPages = computed(() => {
  const current = props.cases.current_page
  const last    = props.cases.last_page
  const pages   = []
  if (last <= 7) {
    for (let i = 1; i <= last; i++) pages.push(i)
  } else {
    pages.push(1)
    if (current > 3)        pages.push('...')
    for (let i = Math.max(2, current - 1); i <= Math.min(last - 1, current + 1); i++) pages.push(i)
    if (current < last - 2) pages.push('...')
    pages.push(last)
  }
  return pages
})

// ── Form ───────────────────────────────────────────
const caseForm = useForm({
  client_id:    '',
  case_number:  '',
  case_type:    '',
  court:        '',
  opponent:     '',
  start_date:   '',
  next_session: '',
  case_price:   '',
  description:  '',
  status:       'pending',
})

// ── Modal Actions ──────────────────────────────────
const openAddModal = () => {
  isEdit.value         = false
  selectedCase.value   = null
  selectedClient.value = null
  clientSearch.value   = ''
  caseForm.reset()
  modals.form = true
}

const openEditModal = (legalCase) => {
  isEdit.value          = true
  selectedCase.value    = legalCase
  caseForm.client_id    = legalCase.client_id    || ''
  caseForm.case_number  = legalCase.case_number  || ''
  caseForm.case_type    = legalCase.case_type    || ''
  caseForm.court        = legalCase.court        || ''
  caseForm.opponent     = legalCase.opponent     || ''
  caseForm.start_date   = legalCase.start_date   || ''
  caseForm.next_session = legalCase.next_session || ''
  caseForm.case_price   = legalCase.case_price   || ''
  caseForm.description  = legalCase.description  || ''
  caseForm.status       = legalCase.status       || 'pending'
  modals.form = true
}

const submitForm = () => {
  if (isEdit.value) {
    caseForm.put(`/cases/${selectedCase.value.id}`, {
      onSuccess: () => { modals.form = false; toast.success('تم التعديل بنجاح') },
    })
  } else {
    caseForm.post('/cases', {
      onSuccess: () => { modals.form = false; toast.success('تمت الإضافة بنجاح') },
    })
  }
}

const openDeleteConfirm = (legalCase) => {
  selectedCase.value     = legalCase
  deleteProcessing.value = false
  modals.delete          = true
}

const confirmDelete = () => {
  deleteProcessing.value = true
  router.delete(route('cases.destroy', selectedCase.value.id), {
    onSuccess: () => {
      modals.delete          = false
      deleteProcessing.value = false
      toast.success('تم حذف القضية بنجاح')
    },
    onError: () => {
      deleteProcessing.value = false
      toast.error('حدث خطأ أثناء الحذف')
    },
  })
}

// ── Helpers ────────────────────────────────────────
const formatDate = (date) => {
  if (!date) return null
  return new Date(date).toLocaleDateString('ar-EG', {
    year: 'numeric', month: 'short', day: 'numeric',
  })
}

const translateStatus = (s) => ({
  pending: 'قيد الإعداد',
  open:    'نشطة',
  closed:  'مغلقة',
}[s] || s)

const statusPill = (s) => {
  if (s === 'open')   return 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20'
  if (s === 'closed') return 'bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-white/10'
  return 'bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-500/20'
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
  background-color: #0000004d;
}
.err-msg {
  font-size: 10px;
  font-weight: 700;
  color: #ef4444;
  margin-top: 4px;
}
</style>

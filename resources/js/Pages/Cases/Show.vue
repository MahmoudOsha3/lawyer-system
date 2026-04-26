<template>
  <Head :title="'قضية رقم: ' + legalCase.case_number" />

  <DashboardLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-0 pb-20">

      <!-- Top Bar -->
      <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-5">
        <div>
          <div class="flex items-center gap-2 text-xs text-slate-400 mb-2">
            <Link href="/cases" class="hover:text-[#d4af37] transition-colors">القضايا</Link>
            <span>›</span>
            <span class="text-slate-600 dark:text-slate-300">{{ legalCase.case_number }}</span>
          </div>
          <div class="flex items-center gap-3 flex-wrap">
            <h1 class="text-xl font-bold text-slate-800 dark:text-white">قضية رقم {{ legalCase.case_number }}</h1>
            <span :class="statusPill(legalCase.status)" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium">
              <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
              {{ translateStatus(legalCase.status) }}
            </span>
          </div>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ legalCase.court }}</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
          <button @click="openModal('session')"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-medium bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all">
            <i class="fas fa-calendar-plus text-xs"></i> إضافة جلسة
          </button>
          <button @click="openModal('payment')"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-medium bg-[#d4af37] text-[#1e272e] hover:bg-[#b8962d] transition-all">
            <i class="fas fa-coins text-xs"></i> تسجيل دفعة
          </button>
          <button @click="openModal('file')"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-medium border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
            <i class="fas fa-upload text-xs"></i> رفع مستند
          </button>
        </div>
      </div>

      <!-- Stats Row 1: أتعاب -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
        <div class="bg-slate-100 dark:bg-white/[0.03] border border-slate-200 dark:border-white/5 rounded-xl p-4">
          <p class="text-[10px] font-medium text-slate-500 uppercase tracking-wider mb-1.5">إجمالي الأتعاب</p>
          <p class="text-xl font-bold text-slate-900 dark:text-white">{{ formatNum(legalCase.case_price) }} <span class="text-xs text-slate-400 font-normal">ج.م</span></p>
        </div>
        <div class="bg-[#EAF3DE] dark:bg-emerald-500/[0.06] border border-[#C0DD97] dark:border-emerald-500/20 rounded-xl p-4">
          <p class="text-[10px] font-medium text-[#3B6D11] dark:text-emerald-400 uppercase tracking-wider mb-1.5">أتعاب مدفوعة</p>
          <p class="text-xl font-bold text-[#27500A] dark:text-emerald-400">{{ formatNum(totalPaid) }} <span class="text-xs text-[#639922] font-normal">ج.م</span></p>
        </div>
        <div class="bg-[#FCEBEB] dark:bg-red-500/[0.06] border border-[#F7C1C1] dark:border-red-500/20 rounded-xl p-4">
          <p class="text-[10px] font-medium text-[#A32D2D] dark:text-red-400 uppercase tracking-wider mb-1.5">أتعاب متبقية</p>
          <p class="text-xl font-bold text-[#791F1F] dark:text-red-400">{{ formatNum(remainingAmount) }} <span class="text-xs text-[#E24B4A] font-normal">ج.م</span></p>
        </div>
        <div class="bg-[#FAEEDA] dark:bg-amber-500/[0.06] border border-[#FAC775] dark:border-amber-500/20 rounded-xl p-4">
          <p class="text-[10px] font-medium text-[#854F0B] dark:text-amber-400 uppercase tracking-wider mb-1.5">نسبة السداد</p>
          <p class="text-xl font-bold text-[#633806] dark:text-amber-400">{{ paymentPercent }}<span class="text-xs text-[#BA7517] font-normal"> %</span></p>
        </div>
      </div>

      <!-- Stats Row 2: مصاريف -->
      <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-5">
        <div class="bg-slate-100 dark:bg-white/[0.03] border border-slate-200 dark:border-white/5 rounded-xl p-4">
          <p class="text-[10px] font-medium text-slate-500 uppercase tracking-wider mb-1.5">إجمالي المصاريف</p>
          <p class="text-xl font-bold text-slate-900 dark:text-white">{{ formatNum(totalExpenses) }} <span class="text-xs text-slate-400 font-normal">ج.م</span></p>
        </div>
        <div class="bg-[#EAF3DE] dark:bg-emerald-500/[0.06] border border-[#C0DD97] dark:border-emerald-500/20 rounded-xl p-4">
          <p class="text-[10px] font-medium text-[#3B6D11] dark:text-emerald-400 uppercase tracking-wider mb-1.5">مصاريف مدفوعة</p>
          <p class="text-xl font-bold text-[#27500A] dark:text-emerald-400">{{ formatNum(totalExpensesPaid) }} <span class="text-xs text-[#639922] font-normal">ج.م</span></p>
        </div>
        <div class="bg-[#FCEBEB] dark:bg-red-500/[0.06] border border-[#F7C1C1] dark:border-red-500/20 rounded-xl p-4">
          <p class="text-[10px] font-medium text-[#A32D2D] dark:text-red-400 uppercase tracking-wider mb-1.5">مصاريف غير مدفوعة</p>
          <p class="text-xl font-bold text-[#791F1F] dark:text-red-400">{{ formatNum(totalExpensesPending) }} <span class="text-xs text-[#E24B4A] font-normal">ج.م</span></p>
        </div>
      </div>

      <!-- Tabs -->
      <div class="flex gap-0 border-b border-slate-200 dark:border-white/5 mb-5">
        <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key"
          :class="activeTab === tab.key
            ? 'border-b-2 border-[#d4af37] text-[#b8962d] dark:text-[#d4af37]'
            : 'text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 border-b-2 border-transparent'"
          class="px-5 py-2.5 text-xs transition-all -mb-px font-bold">
          {{ tab.label }}
        </button>
      </div>

      <!-- Main Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        <div class="lg:col-span-2 space-y-4">

          <!-- Tab: Details -->
          <div v-if="activeTab === 'details'" class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 text-sm font-medium dark:text-white">بيانات القضية</div>
            <div class="grid grid-cols-2 divide-x divide-x-reverse divide-slate-100 dark:divide-white/5">
              <div v-for="(item, i) in caseDetails" :key="i"
                :class="i >= caseDetails.length - 2 ? '' : 'border-b border-slate-100 dark:border-white/5'"
                class="px-5 py-3.5">
                <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider mb-1">{{ item.label }}</p>
                <p class="text-sm text-slate-700 dark:text-slate-200" :class="item.class || ''">{{ item.value || '---' }}</p>
              </div>
            </div>
            <div class="px-5 py-4 border-t border-slate-100 dark:border-white/5">
              <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider mb-2">وصف القضية</p>
              <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ legalCase.description || 'لا يوجد وصف مضاف.' }}</p>
            </div>
          </div>

          <!-- Tab: Sessions -->
          <div v-if="activeTab === 'sessions'" class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex justify-between items-center">
              <span class="text-sm font-medium dark:text-white">سجل الجلسات</span>
              <span class="text-[10px] text-slate-400">{{ legalCase.sessions?.length || 0 }} جلسات</span>
            </div>
            <div v-if="legalCase.sessions?.length" class="divide-y divide-slate-100 dark:divide-white/5">
              <div v-for="(session, i) in legalCase.sessions" :key="session.id" class="flex gap-4 items-start px-5 py-4">
                <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <i class="fas fa-gavel text-xs text-amber-600 dark:text-amber-400"></i>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-bold text-slate-900 dark:text-white mb-1">جلسة بتاريخ {{ formatDate(session.session_date) }}</p>
                  <p class="text-xs text-slate-400 leading-relaxed mb-2">{{ session.session_result || 'في انتظار نتيجة الجلسة...' }}</p>
                  <span v-if="session.next_session"
                    class="inline-flex items-center gap-1.5 text-[10px] font-medium bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-500/20 px-2.5 py-1 rounded-lg">
                    <i class="far fa-calendar-alt text-[10px]"></i>
                    الجلسة القادمة: {{ formatDate(session.next_session) }}
                  </span>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0">
                  <button @click="openModal('editSession', session)"
                    class="w-7 h-7 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-blue-500 hover:border-blue-300 dark:hover:border-blue-500/40 transition-all">
                    <i class="fas fa-pen text-[10px]"></i>
                  </button>
                  <button @click="openConfirmDelete('session', session.id, 'الجلسة رقم ' + (i + 1))"
                    class="w-7 h-7 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-300 dark:hover:border-red-500/40 transition-all">
                    <i class="fas fa-trash text-[10px]"></i>
                  </button>
                </div>
              </div>
            </div>
            <div v-else class="py-16 text-center text-sm text-slate-400">لا يوجد جلسات مسجلة بعد.</div>
          </div>

          <!-- Tab: Financials -->
          <div v-if="activeTab === 'financials'" class="space-y-4">

            <!-- سجل الدفعات -->
            <div class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden">
              <div class="px-5 py-3.5 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex justify-between items-center">
                <span class="text-sm font-medium dark:text-white">سجل الدفعات</span>
                <button @click="openModal('payment')" class="text-[10px] font-medium px-3 py-1.5 rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 transition-colors">+ إضافة دفعة</button>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                  <thead class="bg-slate-50 dark:bg-black/20 text-[10px] font-medium text-slate-900 uppercase">
                    <tr>
                      <th class="px-5 py-3">التاريخ</th>
                      <th class="px-5 py-3">المبلغ</th>
                      <th class="px-5 py-3">ملاحظات</th>
                      <th class="px-5 py-3 text-center">العمليات</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                    <tr v-for="p in legalCase.payments" :key="p.id" class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01]">
                      <td class="px-5 py-3.5 font-mono text-slate-500 dark:text-slate-900 text-sm font-bold">{{ formatDate(p.payment_date) }}</td>
                      <td class="px-5 py-3.5 font-bold text-emerald-600 dark:text-emerald-400">{{ formatNum(p.amount) }} ج.م</td>
                      <td class="px-5 py-3.5 text-slate-400">{{ p.notes || '---' }}</td>
                      <td class="px-5 py-3.5">
                        <div class="flex items-center justify-center gap-1.5">
                          <button @click="openModal('editPayment', p)"
                            class="w-7 h-7 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-blue-500 hover:border-blue-300 dark:hover:border-blue-500/40 transition-all">
                            <i class="fas fa-pen text-[10px]"></i>
                          </button>
                          <button @click="openConfirmDelete('payment', p.id, 'الدفعة بمبلغ ' + formatNum(p.amount) + ' ج.م')"
                            class="w-7 h-7 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-300 dark:hover:border-red-500/40 transition-all">
                            <i class="fas fa-trash text-[10px]"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr v-if="!legalCase.payments?.length">
                      <td colspan="4" class="px-5 py-10 text-center text-slate-400">لا توجد دفعات مسجلة</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- سجل المصاريف -->
            <div class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden">
              <div class="px-5 py-3.5 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex justify-between items-center">
                <span class="text-sm font-medium dark:text-white">سجل المصاريف</span>
                <button @click="openModal('expense')" class="text-[10px] font-medium px-3 py-1.5 rounded-lg bg-red-500 text-white hover:bg-red-600 transition-colors">+ إضافة مصاريف</button>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                  <thead class="bg-slate-50 dark:bg-black/20 text-[10px] font-medium text-slate-900 uppercase">
                    <tr>
                      <th class="px-5 py-3">البيان</th>
                      <th class="px-5 py-3">التاريخ</th>
                      <th class="px-5 py-3">المبلغ</th>
                      <th class="px-5 py-3">حالة دفع الموكل</th>
                      <th class="px-5 py-3 text-center">العمليات</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                    <tr v-for="e in legalCase.expenses" :key="e.id" class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01]">
                      <td class="px-5 py-3.5 font-medium text-slate-600 dark:text-slate-300">{{ e.title }}</td>
                      <td class="px-5 py-3.5 font-mono text-slate-900 text-sm font-bold">{{ formatDate(e.expense_date) }}</td>
                      <td class="px-5 py-3.5 font-bold text-red-600 dark:text-red-400">{{ formatNum(e.amount) }} ج.م</td>
                      <td class="px-5 py-3.5">
                        <span v-if="e.status === 'paid'"
                          class="inline-flex items-center gap-1 text-[10px] font-medium bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 px-2.5 py-1 rounded-full">
                          <i class="fas fa-check text-[9px]"></i> تم الدفع
                        </span>
                        <span v-else
                          class="inline-flex items-center gap-1 text-[10px] font-medium bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-500/20 px-2.5 py-1 rounded-full">
                          <i class="fas fa-clock text-[9px]"></i> لم يسدد
                        </span>
                      </td>
                      <td class="px-5 py-3.5">
                        <div class="flex items-center justify-center gap-1.5">
                          <!-- تعديل المصروف -->
                          <button v-if="e.status !== 'paid'" @click="openModal('editExpense', e)"
                            class="w-7 h-7 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-blue-500 hover:border-blue-300 dark:hover:border-blue-500/40 transition-all">
                            <i class="fas fa-pen text-[10px]"></i>
                          </button>
                          <button v-if="e.status !== 'paid'" @click="openPayExpenseConfirm(e)"
                            class="text-[10px] font-medium px-2.5 py-1.5 rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 transition-colors whitespace-nowrap">
                            <i class="fas fa-check ml-1 text-[9px]"></i> تأكيد الدفع
                          </button>
                          <!-- حذف المصروف -->
                          <button @click="openConfirmDelete('expense', e.id, 'مصروف ' + e.title)"
                            class="w-7 h-7 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-300 dark:hover:border-red-500/40 transition-all">
                            <i class="fas fa-trash text-[10px]"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr v-if="!legalCase.expenses?.length">
                      <td colspan="5" class="px-5 py-10 text-center text-slate-400">لا توجد مصاريف مسجلة</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Tab: Files -->
          <div v-if="activeTab === 'files'" class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex justify-between items-center">
              <span class="text-sm font-medium dark:text-white">المستندات المرفقة</span>
              <button @click="openModal('file')" class="text-[10px] font-medium px-3 py-1.5 rounded-lg border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">+ رفع مستند</button>
            </div>
            <div v-if="legalCase.files?.length" class="divide-y divide-slate-50 dark:divide-white/5">
                <div v-for="f in legalCase.files" :key="f.id" class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50/50 dark:hover:bg-white/[0.01]">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-500/10 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-pdf text-xs text-red-500"></i>
                    </div>
                    <div>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-200">{{ f.title }}</p>
                    <p class="text-[10px] text-slate-400">
                        صيغة: {{ f.file_type }} - الحجم: {{ f.file_size }}
                    </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a :href="'/case-file/download/' + f.id" target="_blank" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-[#d4af37] hover:border-[#d4af37]/30 transition-all">
                    <i class="fas fa-download text-xs"></i>
                    </a>
                    <button @click="openConfirmDelete('file', f.id, 'المستند ' + f.title)" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-300 dark:hover:border-red-500/40 transition-all">
                    <i class="fas fa-trash text-xs"></i>
                    </button>
                </div>
                </div>
            </div>
            <div v-else class="py-16 text-center text-sm text-slate-400">لا توجد مستندات مرفقة.</div>
          </div>

        </div>

        <!-- Sidebar -->
        <div class="space-y-4">

          <div class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 text-xs font-medium text-slate-400">الجلسة القادمة</div>
            <div class="p-4">
              <div v-if="legalCase.next_session">
                <p class="text-lg font-bold text-slate-800 dark:text-white mb-0.5">{{ formatDate(legalCase.next_session) }}</p>
                <p class="text-xs text-slate-400 mb-3">{{ legalCase.court }}</p>
                <span v-if="daysUntilSession !== null"
                  :class="daysUntilSession <= 3 ? 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border-red-200 dark:border-red-500/20' : 'bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-500/20'"
                  class="inline-block text-[10px] font-medium px-2.5 py-1 rounded-lg border">
                  {{ daysUntilSession === 0 ? 'اليوم' : daysUntilSession > 0 ? 'بعد ' + daysUntilSession + ' يوم' : 'انتهت' }}
                </span>
              </div>
              <p v-else class="text-sm text-slate-400 italic">لم يتم تحديد جلسة قادمة</p>
            </div>
          </div>

          <div class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 p-4">
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-3">نسبة السداد</p>
            <div class="flex justify-between text-xs mb-2">
              <span class="text-slate-400">المدفوع من الأتعاب</span>
              <span class="font-bold text-slate-700 dark:text-white">{{ paymentPercent }}%</span>
            </div>
            <div class="h-1.5 bg-slate-100 dark:bg-white/5 rounded-full overflow-hidden mb-3">
              <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" :style="{ width: paymentPercent + '%' }"></div>
            </div>
            <div class="grid grid-cols-2 gap-2">
              <div class="bg-slate-50 dark:bg-black/20 rounded-xl p-3 text-center">
                <p class="text-[10px] text-slate-400 mb-1">مدفوع</p>
                <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ formatNum(totalPaid) }}</p>
              </div>
              <div class="bg-slate-50 dark:bg-black/20 rounded-xl p-3 text-center">
                <p class="text-[10px] text-slate-400 mb-1">متبقي</p>
                <p class="text-sm font-bold text-amber-600 dark:text-amber-400">{{ formatNum(remainingAmount) }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 text-xs font-medium text-slate-400">معلومات الموكل</div>
            <div class="p-4">
              <div class="flex items-center gap-3 mb-3 pb-3 border-b border-slate-100 dark:border-white/5">
                <div class="w-9 h-9 rounded-full bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-sm font-bold text-amber-600 dark:text-amber-400 flex-shrink-0">
                  {{ legalCase.client?.name?.charAt(0) }}
                </div>
                <div>
                  <p class="text-sm font-medium text-slate-700 dark:text-white">{{ legalCase.client?.name }}</p>
                  <p class="text-[10px] text-slate-400">{{ legalCase.client?.type === 'person' ? 'موكل فرد' : 'شركة' }}</p>
                </div>
              </div>
              <div class="space-y-2 text-xs">
                <div class="flex justify-between">
                  <span class="text-slate-400">الهاتف</span>
                  <span class="text-slate-700 dark:text-slate-300">{{ legalCase.client?.phone || '---' }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-400">العنوان</span>
                  <span class="text-slate-700 dark:text-slate-300">{{ legalCase.client?.address || '---' }}</span>
                </div>
              </div>
              <Link :href="`/clients/${legalCase.client_id}`"
                class="mt-3 w-full flex items-center justify-center gap-2 text-xs font-medium text-[#b8962d] border border-amber-200 dark:border-amber-500/20 bg-amber-50 dark:bg-amber-500/10 rounded-xl py-2 hover:bg-amber-100 dark:hover:bg-amber-500/20 transition-colors">
                عرض ملف الموكل <i class="fas fa-external-link-alt text-[10px]"></i>
              </Link>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- ===== MODAL: إضافة / تعديل ===== -->
    <div v-if="modals.show" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
      <div class="bg-white dark:bg-[#111827] w-full max-w-lg rounded-2xl shadow-2xl border border-slate-200 dark:border-white/10 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-white/5 flex justify-between items-center bg-slate-50/50 dark:bg-black/20">
          <h3 class="font-bold text-slate-800 dark:text-white text-sm">{{ modalTitle }}</h3>
          <button @click="closeModal" class="w-7 h-7 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-200 transition-all">
            <i class="fas fa-times text-xs"></i>
          </button>
        </div>
        <form @submit.prevent="submitForm" class="p-6 space-y-4 text-right">

          <template v-if="modals.type === 'session' || modals.type === 'editSession'">
            <div><label class="form-label">تاريخ الجلسة *</label><input v-model="sessionForm.session_date" type="date" class="form-input" required></div>
            <div><label class="form-label">نتيجة الجلسة</label><textarea v-model="sessionForm.session_result" rows="3" class="form-input resize-none" placeholder="اكتب ما تم في الجلسة..."></textarea></div>
            <div><label class="form-label">موعد الجلسة القادمة</label><input v-model="sessionForm.next_session" type="date" class="form-input"></div>
          </template>

          <template v-if="modals.type === 'payment' || modals.type === 'editPayment'">
            <div><label class="form-label">المبلغ المدفوع (ج.م) *</label><input v-model="paymentForm.amount" type="number" step="0.01" class="form-input" placeholder="0.00" required></div>
            <div><label class="form-label">تاريخ الدفع *</label><input v-model="paymentForm.payment_date" type="date" class="form-input" required></div>
            <div><label class="form-label">ملاحظات</label><input v-model="paymentForm.notes" type="text" class="form-input" placeholder="مثلاً: قسط شهر مارس"></div>
          </template>

          <template v-if="modals.type === 'expense' || modals.type === 'editExpense'">
            <div><label class="form-label">بيان المصاريف *</label><input v-model="expenseForm.title" type="text" class="form-input" placeholder="مثلاً: رسوم دمغة / انتقالات" required></div>
            <div><label class="form-label">المبلغ (ج.م) *</label><input v-model="expenseForm.amount" type="number" step="0.01" class="form-input" placeholder="0.00" required></div>
            <div><label class="form-label">التاريخ *</label><input v-model="expenseForm.expense_date" type="date" class="form-input" required></div>
          </template>

          <template v-if="modals.type === 'file'">
            <div><label class="form-label">اسم المستند *</label><input v-model="fileForm.title" type="text" class="form-input" placeholder="مثلاً: صورة التوكيل" required></div>
            <div><label class="form-label">اختر الملف *</label><input @input="fileForm.file = $event.target.files[0]" type="file" class="form-input py-2.5"></div>
          </template>

          <div class="flex gap-3 pt-2">
            <button type="button" @click="closeModal" class="flex-1 py-3 text-xs font-bold text-slate-500 bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition-all">إلغاء</button>
            <button type="submit" :disabled="isProcessing" class="flex-[2] py-3 bg-[#d4af37] text-[#1e272e] font-bold rounded-xl hover:bg-[#b8962d] transition-all flex items-center justify-center gap-2 disabled:opacity-60">
              <i v-if="isProcessing" class="fas fa-spinner fa-spin text-xs"></i>
              <i v-else class="fas fa-save text-xs"></i>
              {{ modals.type.startsWith('edit') ? 'حفظ التعديلات' : 'حفظ البيانات' }}
            </button>
          </div>

        </form>
      </div>
    </div>

    <!-- ===== MODAL: تأكيد دفع المصروف ===== -->
    <div v-if="payExpenseConfirm.show" class="fixed inset-0 z-[105] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
      <div class="bg-white dark:bg-[#111827] w-full max-w-sm rounded-2xl shadow-2xl border border-slate-200 dark:border-white/10 overflow-hidden">
        <div class="px-6 pt-8 pb-5 text-center">
          <div class="w-16 h-16 rounded-full bg-emerald-50 dark:bg-emerald-500/10 border-4 border-emerald-100 dark:border-emerald-500/20 flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check-circle text-2xl text-emerald-500"></i>
          </div>
          <h3 class="text-base font-black text-slate-800 dark:text-white mb-2">تأكيد دفع المصروف</h3>
          <p class="text-xs font-bold text-slate-500 dark:text-slate-400 leading-relaxed">
            هل تأكد أن الموكل سدّد مصروف
            <span class="font-black text-slate-700 dark:text-slate-200">{{ payExpenseConfirm.expense?.title }}</span>
            بمبلغ
            <span class="font-black text-emerald-600 dark:text-emerald-400">{{ formatNum(payExpenseConfirm.expense?.amount) }} ج.م</span>
            ؟
          </p>
        </div>
        <div class="flex gap-3 px-6 pb-6">
          <button @click="payExpenseConfirm.show = false"
            class="flex-1 py-2.5 text-xs font-black text-slate-500 bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition-all border border-slate-200 dark:border-white/10">
            إلغاء
          </button>
          <button @click="confirmPayExpense" :disabled="payExpenseConfirm.processing"
            class="flex-1 py-2.5 text-xs font-black text-white bg-emerald-500 rounded-xl hover:bg-emerald-600 transition-all flex items-center justify-center gap-2 disabled:opacity-60">
            <i v-if="payExpenseConfirm.processing" class="fas fa-spinner fa-spin text-xs"></i>
            <i v-else class="fas fa-check text-xs"></i>
            نعم، تأكيد الدفع
          </button>
        </div>
      </div>
    </div>

    <!-- ===== MODAL: تأكيد الحذف ===== -->
    <div v-if="deleteConfirm.show" class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
      <div class="bg-white dark:bg-[#111827] w-full max-w-sm rounded-2xl shadow-2xl border border-slate-200 dark:border-white/10 overflow-hidden">
        <div class="px-6 pt-8 pb-5 text-center">
          <div class="w-16 h-16 rounded-full bg-red-50 dark:bg-red-500/10 border-4 border-red-100 dark:border-red-500/20 flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-trash-alt text-2xl text-red-500"></i>
          </div>
          <h3 class="text-base font-black text-slate-800 dark:text-white mb-2">تأكيد الحذف</h3>
          <p class="text-xs font-bold text-slate-500 dark:text-slate-400 leading-relaxed">
            هل أنت متأكد من حذف <span class="font-black text-slate-700 dark:text-slate-200"> {{ deleteConfirm.label }} </span>؟
          </p>
          <p class="text-[10px] font-black text-red-500 mt-1">لا يمكن التراجع عن هذا الإجراء.</p>
        </div>
        <div class="flex gap-3 px-6 pb-6">
          <button @click="deleteConfirm.show = false"
            class="flex-1 py-2.5 text-xs font-black text-slate-500 bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition-all border border-slate-200 dark:border-white/10">
            إلغاء
          </button>
          <button @click="confirmDelete" :disabled="deleteConfirm.processing"
            class="flex-1 py-2.5 text-xs font-black text-white bg-red-500 rounded-xl hover:bg-red-600 transition-all flex items-center justify-center gap-2 disabled:opacity-60">
            <i v-if="deleteConfirm.processing" class="fas fa-spinner fa-spin text-xs"></i>
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
import { Head, Link, useForm, router, usePage } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { toast } from 'vue-sonner'

const props = defineProps({ legalCase: Object })

const page      = usePage()
const activeTab = ref('details')

const tabs = [
  { key: 'details',    label: 'تفاصيل القضية' },
  { key: 'sessions',   label: 'الجلسات' },
  { key: 'financials', label: 'الماليات' },
  { key: 'files',      label: 'المستندات' },
]

// ── Modals State ───────────────────────────────
const modals = reactive({ show: false, type: '', editId: null })

const deleteConfirm = reactive({
  show: false, type: '', id: null, label: '', processing: false,
})

// ── Pay Expense Confirm Modal ──────────────────
const payExpenseConfirm = reactive({
  show: false, expense: null, processing: false,
})

const openPayExpenseConfirm = (expense) => {
  payExpenseConfirm.expense    = expense
  payExpenseConfirm.processing = false
  payExpenseConfirm.show       = true
}

const confirmPayExpense = () => {
  payExpenseConfirm.processing = true
  router.patch(
    `/case-expenses/${payExpenseConfirm.expense.id}/pay`,
    { expense_id: payExpenseConfirm.expense.id, status: 'paid' },
    {
      preserveScroll: true,
      onSuccess: () => {
        payExpenseConfirm.show       = false
        payExpenseConfirm.processing = false
      },
      onError: () => {
        payExpenseConfirm.processing = false
        toast.error('حدث خطأ')
      },
    }
  )
}

// ── Forms ──────────────────────────────────────
const sessionForm = useForm({
  case_id: props.legalCase.id, session_date: '', session_result: '', next_session: '',
})

const paymentForm = useForm({
  case_id: props.legalCase.id, amount: '',
  payment_date: new Date().toISOString().substr(0, 10), notes: '',
})

const expenseForm = useForm({
  case_id: props.legalCase.id, title: '', amount: '',
  expense_date: new Date().toISOString().substr(0, 10), notes: '',
})

const fileForm = useForm({ case_id: props.legalCase.id, title: '', file: null })

watch(() => page.props.flash, (flash) => {
  if (flash?.success) toast.success(flash.success)
  if (flash?.error)   toast.error(flash.error)
}, { immediate: true })

const totalPaid = computed(() =>
  props.legalCase.payments?.reduce((s, p) => s + Number(p.amount), 0) || 0)
const totalExpenses = computed(() =>
  props.legalCase.expenses?.reduce((s, e) => s + Number(e.amount), 0) || 0)
const totalExpensesPaid = computed(() =>
  props.legalCase.expenses?.filter(e => e.status === 'paid').reduce((s, e) => s + Number(e.amount), 0) || 0)
const totalExpensesPending = computed(() =>
  props.legalCase.expenses?.filter(e => e.status !== 'paid').reduce((s, e) => s + Number(e.amount), 0) || 0)
const remainingAmount = computed(() => (props.legalCase.case_price || 0) - totalPaid.value)
const paymentPercent = computed(() => {
  if (!props.legalCase.case_price) return 0
  return Math.min(100, Math.round((totalPaid.value / props.legalCase.case_price) * 100))
})
const daysUntilSession = computed(() => {
  if (!props.legalCase.next_session) return null
  return Math.ceil((new Date(props.legalCase.next_session) - new Date()) / (1000 * 60 * 60 * 24))
})
const caseDetails = computed(() => [
  { label: 'الموكل',      value: props.legalCase.client?.name,  class: 'text-blue-600 dark:text-blue-400' },
  { label: 'الخصم',       value: props.legalCase.opponent },
  { label: 'المحكمة',     value: props.legalCase.court },
  { label: 'حالة القضية', value: props.legalCase.status === 'open' ? 'مفتوحة' : props.legalCase.status === 'closed' ? 'مغلقة' : 'قيد النظر' },
  { label: 'نوع القضية',  value: props.legalCase.case_type,     class: 'text-amber-600 dark:text-amber-400' },
  { label: 'تاريخ البدء', value: formatDate(props.legalCase.start_date) },
])
const modalTitle = computed(() => ({
  session:     'إضافة جلسة جديدة',
  editSession: 'تعديل بيانات الجلسة',
  payment:     'تسجيل دفعة أتعاب',
  editPayment: 'تعديل بيانات الدفعة',
  expense:     'تسجيل مصاريف قضائية',
  editExpense: 'تعديل بيانات المصروف',
  file:        'رفع مستند للملف',
}[modals.type] || ''))
const isProcessing = computed(() =>
  sessionForm.processing || paymentForm.processing || expenseForm.processing || fileForm.processing)

const openModal = (type, record = null) => {
  modals.type   = type
  modals.editId = record?.id || null

  if (type === 'editSession' && record) {
    sessionForm.session_date   = record.session_date   ? record.session_date.substring(0, 10)   : ''
    sessionForm.session_result = record.session_result || ''
    sessionForm.next_session   = record.next_session   ? record.next_session.substring(0, 10)   : ''
  }
  if (type === 'editPayment' && record) {
    paymentForm.amount       = record.amount       || ''
    paymentForm.payment_date = record.payment_date ? record.payment_date.substring(0, 10) : ''
    paymentForm.notes        = record.notes        || ''
  }
  if (type === 'editExpense' && record) {
    expenseForm.title        = record.title        || ''
    expenseForm.amount       = record.amount       || ''
    expenseForm.expense_date = record.expense_date ? record.expense_date.substring(0, 10) : ''
    expenseForm.notes        = record.notes        || ''
  }

  modals.show = true
}

const closeModal = () => {
  modals.show = false; modals.type = ''; modals.editId = null
  sessionForm.reset(); paymentForm.reset(); expenseForm.reset(); fileForm.reset()
}

const submitForm = () => {
  const opts = {
    onSuccess: () => { closeModal() },
    onError: (errors) => {
      const first = Object.values(errors)[0]
      toast.error(first || 'حدث خطأ، يرجى مراجعة البيانات')
    },
  }
  if (modals.type === 'session')     sessionForm.post(`/sessions`, opts)
  if (modals.type === 'editSession') sessionForm.put(`/sessions/${modals.editId}`, opts)
  if (modals.type === 'payment')     paymentForm.post(`/case-payments`, opts)
  if (modals.type === 'editPayment') paymentForm.put(`/case-payments/${modals.editId}`, opts)
  if (modals.type === 'expense')     expenseForm.post(`/case-expenses`, opts)
  if (modals.type === 'editExpense') expenseForm.put(`/case-expenses/${modals.editId}`, opts)
  if (modals.type === 'file')        fileForm.post(`/case-files`, opts)
}

const openConfirmDelete = (type, id, label) => {
  deleteConfirm.type = type; deleteConfirm.id = id; deleteConfirm.label = label
  deleteConfirm.processing = false; deleteConfirm.show = true
}

const confirmDelete = () => {
  deleteConfirm.processing = true
  const routes = {
    session: `/sessions/${deleteConfirm.id}`,
    payment: `/case-payments/${deleteConfirm.id}`,
    expense: `/case-expenses/${deleteConfirm.id}`,
    file:    `/case-files/${deleteConfirm.id}`,
  }
  router.delete(routes[deleteConfirm.type], {
    onSuccess: () => { deleteConfirm.show = false; deleteConfirm.processing = false },
    onError:   () => { deleteConfirm.processing = false; toast.error('حدث خطأ أثناء الحذف') },
  })
}

// ── Helpers ─────────────────────────────────────
function formatDate(date) {
  if (!date) return '---'
  return new Date(date).toLocaleDateString('ar-EG')
}
function formatNum(val) { return Number(val || 0).toLocaleString('ar-EG') }
const translateStatus = (s) => ({ pending: 'قيد الانتظار', open: 'نشطة', closed: 'مغلقة' }[s] || s)
const statusPill = (s) => {
  if (s === 'open')   return 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20'
  if (s === 'closed') return 'bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-white/10'
  return 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20'
}
</script>

<style scoped>
.form-label { display: block; font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.375rem; letter-spacing: 0.08em; }
.form-input { width: 100%; background-color: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 0.625rem; padding: 0.75rem 0.875rem; font-size: 0.875rem; outline: none; transition: all 0.2s; color: #1e293b; }
.form-input:focus { border-color: #d4af37; background-color: #fff; box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); }
:where(.dark) .form-input { background-color: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.08); color: white; }
:where(.dark) .form-input:focus { border-color: #d4af37; background-color: rgba(0,0,0,0.3); }
</style>

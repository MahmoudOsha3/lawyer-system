<template>
  <Head :title="'ملف الموكل | ' + client.name" />

  <DashboardLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-0 py-8 space-y-6">

      <!-- ── Header ── -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
          <!-- Avatar -->
          <div class="w-14 h-14 rounded-2xl bg-[#FAEEDA] dark:bg-amber-500/10 flex items-center justify-center flex-shrink-0 border border-[#FAC775] dark:border-amber-500/20">
            <span class="text-2xl font-black text-[#854F0B] dark:text-amber-400">{{ client.name?.charAt(0) }}</span>
          </div>
          <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
              <Link href="/clients" class="hover:text-[#d4af37] transition-colors font-bold">الموكلين</Link>
              <span>›</span>
              <span class="text-slate-500 dark:text-slate-400 font-bold">ملف الموكل</span>
            </div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ client.name }}</h1>
            <p class="text-xs font-bold text-slate-400 mt-0.5">إدارة النزاعات القضائية والسجلات المالية</p>
          </div>
        </div>

        <button @click="openAddCaseModal"
          class="inline-flex items-center gap-2 bg-[#d4af37] hover:bg-[#b8962d] text-[#1e272e] px-5 py-2.5 rounded-xl font-black text-sm shadow-lg shadow-[#d4af37]/20 transition-all">
          <i class="fas fa-plus-circle"></i> إضافة قضية جديدة
        </button>
      </div>

      <!-- ── Stats ── -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

        <div class="bg-slate-100 dark:bg-white/[0.03] border border-slate-200 dark:border-white/5 rounded-xl p-4">
          <div class="flex items-center justify-between mb-3">
            <div class="w-9 h-9 rounded-lg bg-white dark:bg-white/10 flex items-center justify-center border border-slate-200 dark:border-white/10">
              <i class="fas fa-briefcase text-sm text-slate-500 dark:text-slate-400"></i>
            </div>
          </div>
          <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">إجمالي القضايا</p>
          <p class="text-2xl font-black text-slate-900 dark:text-white">{{ formatNum( stats.total_cases ) }}</p>
        </div>

        <div class="bg-[#FAEEDA] dark:bg-amber-500/[0.06] border border-[#FAC775] dark:border-amber-500/20 rounded-xl p-4">
          <div class="flex items-center justify-between mb-3">
            <div class="w-9 h-9 rounded-lg bg-white/60 dark:bg-white/5 flex items-center justify-center border border-[#FAC775] dark:border-amber-500/20">
              <i class="fas fa-file-invoice-dollar text-sm text-[#854F0B] dark:text-amber-400"></i>
            </div>
          </div>
          <p class="text-[10px] font-black text-[#854F0B] dark:text-amber-400 uppercase tracking-widest mb-1">إجمالي الاتعاب للقضايا</p>
          <p class="text-2xl font-black text-[#633806] dark:text-amber-300">
            {{ formatNum(stats.totalCasesPrice || 0) }}
            <span class="text-xs font-bold text-[#BA7517] dark:text-amber-500 mr-0.5">ج.م</span>
          </p>
        </div>

        <div class="bg-[#EAF3DE] dark:bg-emerald-500/[0.06] border border-[#C0DD97] dark:border-emerald-500/20 rounded-xl p-4">
          <div class="flex items-center justify-between mb-3">
            <div class="w-9 h-9 rounded-lg bg-white/60 dark:bg-white/5 flex items-center justify-center border border-[#C0DD97] dark:border-emerald-500/20">
              <i class="fas fa-hand-holding-usd text-sm text-[#3B6D11] dark:text-emerald-400"></i>
            </div>
          </div>
          <p class="text-[10px] font-black text-[#3B6D11] dark:text-emerald-400 uppercase tracking-widest mb-1"> إجمالي الأتعاب مدفوعة</p>
          <p class="text-2xl font-black text-[#27500A] dark:text-emerald-300">
            {{ formatNum(stats.totalCasesPaid || 0) }}
            <span class="text-xs font-bold text-[#639922] dark:text-emerald-500 mr-0.5">ج.م</span>
          </p>
        </div>

        <div class="bg-[#FCEBEB] dark:bg-red-500/[0.06] border border-[#F7C1C1] dark:border-red-500/20 rounded-xl p-4">
          <div class="flex items-center justify-between mb-3">
            <div class="w-9 h-9 rounded-lg bg-white/60 dark:bg-white/5 flex items-center justify-center border border-[#F7C1C1] dark:border-red-500/20">
              <i class="fas fa-exclamation-circle text-sm text-[#A32D2D] dark:text-red-400"></i>
            </div>
          </div>
          <p class="text-[10px] font-black text-[#A32D2D] dark:text-red-400 uppercase tracking-widest mb-1"> إجمالي الاتعاب المعلقة</p>
          <p class="text-2xl font-black text-[#791F1F] dark:text-red-300">
            {{ formatNum((stats.totalCasesNotPaid) || 0) }}
            <span class="text-xs font-bold text-[#E24B4A] dark:text-red-500 mr-0.5">ج.م</span>
          </p>
        </div>

      </div>

      <!-- ── نسبة السداد ── -->
      <div class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 p-5">
        <div class="flex items-center justify-between mb-3">
          <p class="text-sm font-black text-slate-700 dark:text-white">نسبة سداد الأتعاب</p>
          <span class="text-sm font-black text-slate-800 dark:text-white">{{ paymentPercent }}%</span>
        </div>
        <div class="h-2.5 bg-slate-100 dark:bg-white/5 rounded-full overflow-hidden">
          <div
            class="h-full rounded-full transition-all duration-700"
            :class="paymentPercent >= 80 ? 'bg-emerald-500' : paymentPercent >= 40 ? 'bg-[#d4af37]' : 'bg-red-500'"
            :style="{ width: paymentPercent + '%' }">
          </div>
        </div>
        <div class="flex justify-between mt-2">
          <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">مدفوع {{ formatNum(stats.totalCasesPaid || 0) }} ج.م</span>
          <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">الإجمالي {{ formatNum(stats.totalCasesPrice|| 0) }} ج.م</span>
        </div>
      </div>

      <!-- ── Grid: بيانات + قضايا ── -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- بيانات الموكل -->
        <div class="lg:col-span-1">
          <div class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex items-center gap-2">
              <i class="fas fa-user-circle text-[#d4af37] text-sm"></i>
              <span class="text-sm font-black text-slate-800 dark:text-white">بيانات الموكل الأساسية</span>
            </div>
            <div class="divide-y divide-slate-50 dark:divide-white/5">
              <div v-for="(val, label) in clientDetails" :key="label" class="px-5 py-3.5">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ label }}</p>
                <p class="text-sm font-black text-slate-700 dark:text-slate-200">{{ val || 'غير متوفر' }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- جدول القضايا -->
        <div class="lg:col-span-2">
          <div class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-100 dark:border-white/5 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-black/20 flex items-center justify-between">
              <span class="text-sm font-black text-slate-800 dark:text-white">سجل القضايا</span>
              <span class="text-[10px] font-black bg-[#d4af37]/10 text-[#b8962d] border border-[#d4af37]/20 px-3 py-1 rounded-full">
                إجمالي {{ stats.total_cases }} قضية
              </span>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full text-right">
                <thead class="bg-slate-50 dark:bg-black/20">
                  <tr>
                    <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">رقم القضية</th>
                    <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">الخصم / النوع</th>
                    <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">المحكمة</th>
                    <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">الأتعاب</th>
                    <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">الحالة</th>
                    <th class="px-5 py-3 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">فتح</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                  <tr v-for="c in client.cases || []" :key="c.id"
                    class="hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-all group">
                    <td class="px-5 py-4">
                      <p class="text-sm font-black text-slate-700 dark:text-white">{{ c.case_number }}</p>
                      <p class="text-[11px] font-bold text-blue-500 mt-0.5">
                        <i class="far fa-calendar-alt ml-1 text-[10px]"></i>
                        {{ formatDate(c.next_session) || 'لم يحدد' }}
                      </p>
                    </td>
                    <td class="px-5 py-4">
                      <p class="text-xs font-black text-slate-600 dark:text-slate-300">{{ c.opponent || '---' }}</p>
                      <span class="inline-block mt-1 text-[9px] font-black px-2 py-0.5 rounded-full bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400">
                        {{ c.case_type }}
                      </span>
                    </td>
                    <td class="px-5 py-4 text-xs font-bold text-slate-500 dark:text-slate-400">{{ c.court }}</td>
                    <td class="px-5 py-4">
                      <span class="text-sm font-black text-emerald-600 dark:text-emerald-400">{{ formatNum(c.case_price) }}</span>
                      <span class="text-[10px] font-bold text-slate-400 mr-0.5">ج.م</span>
                    </td>
                    <td class="px-5 py-4">
                      <span :class="statusPill(c.status)" class="inline-flex items-center gap-1.5 text-[10px] font-black px-2.5 py-1 rounded-full border">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                        {{ translateStatus(c.status) }}
                      </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                      <Link :href="`/cases/${c.id}`"
                        class="w-8 h-8 rounded-lg border border-slate-200 dark:border-white/10 inline-flex items-center justify-center text-slate-400 hover:text-[#d4af37] hover:border-[#d4af37]/30 transition-all">
                        <i class="fas fa-external-link-alt text-xs"></i>
                      </Link>
                    </td>
                  </tr>
                  <tr v-if=" client.cases.length === 0">
                    <td colspan="6" class="px-5 py-14 text-center text-sm font-bold text-slate-400">
                      <i class="fas fa-folder-open text-2xl mb-2 block text-slate-300 dark:text-slate-600"></i>
                      لا توجد قضايا مسجلة بعد
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- ===== Modal: إضافة قضية ===== -->
    <div v-if="modals.addCase" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
      <div class="bg-white dark:bg-[#111827] w-full max-w-2xl rounded-2xl shadow-2xl border border-slate-200 dark:border-white/10 overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-100 dark:border-white/5 flex justify-between items-center bg-slate-50/50 dark:bg-black/20">
          <div>
            <h3 class="font-black text-base text-slate-800 dark:text-white">إضافة قضية جديدة</h3>
            <p class="text-[10px] font-bold text-slate-400 mt-0.5">للموكل: {{ client.name }}</p>
          </div>
          <button @click="modals.addCase = false"
            class="w-8 h-8 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-200 transition-all">
            <i class="fas fa-times text-xs"></i>
          </button>
        </div>

        <form @submit.prevent="submitCase" class="p-6">
          <div class="grid grid-cols-2 gap-4 mb-5">

            <div class="col-span-1">
              <label class="form-label">رقم القضية *</label>
              <input v-model="caseForm.case_number" type="text" class="form-input" placeholder="مثال: 1234 لسنة 2026">
            </div>

            <div class="col-span-1">
              <label class="form-label">نوع القضية</label>
              <select v-model="caseForm.case_type" class="form-input appearance-none">
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
            </div>

            <div class="col-span-1">
              <label class="form-label">المحكمة</label>
              <input v-model="caseForm.court" type="text" class="form-input" placeholder="اسم المحكمة والدائرة">
            </div>

            <div class="col-span-1">
              <label class="form-label">الخصم</label>
              <input v-model="caseForm.opponent" type="text" class="form-input" placeholder="اسم الخصم">
            </div>

            <div class="col-span-1">
              <label class="form-label">تاريخ البداية</label>
              <input v-model="caseForm.start_date" type="date" class="form-input">
            </div>

            <div class="col-span-1">
              <label class="form-label">الجلسة القادمة</label>
              <input v-model="caseForm.next_session" type="date" class="form-input">
            </div>

            <div class="col-span-1">
              <label class="form-label">إجمالي الأتعاب (ج.م)</label>
              <input v-model="caseForm.case_price" type="number" class="form-input" placeholder="0.00">
            </div>

            <div class="col-span-1">
              <label class="form-label">حالة القضية</label>
              <select v-model="caseForm.status" class="form-input">
                <option value="pending">جاري الإعداد</option>
                <option value="open">نشطة</option>
                <option value="closed">مغلقة</option>
              </select>
            </div>

            <div class="col-span-2">
              <label class="form-label">وصف القضية / ملاحظات</label>
              <textarea v-model="caseForm.description" rows="3" class="form-input resize-none" placeholder="اكتب ملخص القضية هنا..."></textarea>
            </div>

          </div>

          <div class="flex gap-3">
            <button type="button" @click="modals.addCase = false"
              class="flex-1 py-3 text-sm font-black text-slate-500 bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition-all border border-slate-200 dark:border-white/10">
              إلغاء
            </button>
            <button type="submit" :disabled="caseForm.processing"
              class="flex-[2] py-3 bg-[#d4af37] text-[#1e272e] font-black rounded-xl hover:bg-[#b8962d] transition-all flex items-center justify-center gap-2 disabled:opacity-60 shadow-lg shadow-[#d4af37]/20">
              <i v-if="caseForm.processing" class="fas fa-spinner fa-spin text-sm"></i>
              <i v-else class="fas fa-save text-sm"></i>
              حفظ بيانات القضية
            </button>
          </div>
        </form>

      </div>
    </div>

  </DashboardLayout>
</template>

<script setup>
import { reactive, computed, watch } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { toast } from 'vue-sonner'

const props = defineProps({
    client: Object,
    stats: Array ,
    flash:  Object,
})

const modals = reactive({ addCase: false })

// ── Helpers ─────────────────────────────────────
const formatDate = (date) => {
  if (!date) return null
  return new Date(date).toLocaleDateString('ar-EG')
}
const formatNum = (val) => Number(val || 0).toLocaleString('ar-EG')

const translateStatus = (s) => ({ pending: 'قيد الإعداد', open: 'نشطة', closed: 'مغلقة' }[s] || s)
const statusPill = (s) => {
  if (s === 'open')   return 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20'
  if (s === 'closed') return 'bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-white/10'
  return 'bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-500/20'
}

// ── Computed ────────────────────────────────────
const paymentPercent = computed(() => {
  if (!props.stats.totalCasesPrice) return 0
  return Math.min(100, Math.round(((props.stats.totalCasesPaid || 0) / props.stats.totalCasesPrice) * 100))
})

const clientDetails = computed(() => ({
  'رقم الهاتف':  props.client.phone,
  'الرقم القومي': props.client.national_id,
  'الوظيفة':     props.client.job,
  'العنوان':     props.client.address,
  'نوع الموكل':  props.client.type === 'person' ? 'فرد' : 'شركة',
}))

// ── Case Form ────────────────────────────────────
const caseForm = useForm({
  client_id:   props.client.id,
  case_number: '',
  case_type:   '',
  court:       '',
  opponent:    '',
  start_date:  '',
  next_session:'',
  case_price:  0,
  description: '',
  status:      'pending',
})

const openAddCaseModal = () => {
  caseForm.reset()
  caseForm.client_id = props.client.id
  modals.addCase = true
}

const submitCase = () => {
  caseForm.post('/cases', {
    onSuccess: () => {
      modals.addCase = false
      toast.success('تمت إضافة القضية بنجاح')
    },
    onError: () => toast.error('حدث خطأ، يرجى مراجعة البيانات'),
  })
}

// ── Flash messages ───────────────────────────────
watch(() => props.flash, (val) => {
  if (val?.success) toast.success(val.success)
  if (val?.error)   toast.error(val.error)
}, { deep: true })
</script>

<style scoped>
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

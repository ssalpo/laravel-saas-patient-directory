<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientReportRequest;
use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Services\PatientService;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class PatientController extends Controller
{
    public function __construct(
        protected readonly PatientService $patientService
    ) {
    }

    /**
     * Показывает записи всех колонок пациента
     */
    public function fullRecords(): Response
    {
        $patients = PatientResource::collection(
            Patient::my('created_by')->orderByDesc('created_at')->get()
        );

        return inertia('Patients/FullRecords', compact('patients'));
    }

    /**
     * Возвращает пациентов текущего авторизованного пользователя
     */
    public function index(): Response
    {
        $patients = Patient::my('created_by')
            ->filter(request()?->all())
            ->orderByDesc('created_at')
            ->paginate(100)
            ->onEachSide(0)
            ->withQueryString();

        return inertia('Patients/Index', [
            'filterParams' => request()?->all(),
            'patients' => PatientResource::collection($patients),
        ]);
    }

    /**
     * Возвращает форму добавления пациента
     */
    public function create(): Response
    {
        return inertia('Patients/Edit');
    }

    /**
     * Добавляет нового пациента
     */
    public function store(PatientRequest $request): RedirectResponse
    {
        $patient = $this->patientService->store($request->validated());

        return redirect()->route('patients.show', $patient->id)->with('isCreated', true);
    }

    /**
     * Просмотр карточки пациента
     */
    public function show(int $id): Response
    {
        $patient = Patient::myOrSharedWithMe(auth()->id(), 'created_by')
            ->with('photos')
            ->findOrFail($id);

        $relationKey = $patient->created_by === auth()->id()
            ? 'consultations'
            : 'currentUserConsultations';

        $patient->load([
            $relationKey => fn ($q) => $q->with('user')->orderByDesc('created_at'),
        ]);

        return inertia('Patients/Show', [
            'patient' => PatientResource::make($patient),
        ]);
    }

    /**
     * Возвращает форму редактирования пациента
     */
    public function edit(int $id): Response
    {
        $patient = Patient::my('created_by')->findOrFail($id);

        return inertia('Patients/Edit', [
            'patient' => PatientResource::make($patient),
        ]);
    }

    /**
     * Обновляет данные пациента
     */
    public function update(int $id, PatientRequest $request): RedirectResponse
    {
        $patient = $this->patientService->update(
            $id, $request->validated()
        );

        return redirect()->route('patients.show', $patient->id);
    }

    /**
     * Сохраняет ответ итогового результата диагноза
     */
    public function saveReport(int $id, PatientReportRequest $request): RedirectResponse
    {
        $this->patientService->saveReport($id, $request->validated());

        return back();
    }
}

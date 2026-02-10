<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-secondary">
                <tr>
                    <th class="ps-4">Data Wydania</th>
                    <th>Pracownik</th>
                    <th>Produkt</th>
                    <th>Data ważności / zwrotu</th>
                    <th class="text-end pe-4">Akcje</th>
                </tr>
            </thead>
            <tbody>
            @forelse($activeIssues as $issue)
                <tr>
                    {{-- DATA WYDANIA --}}
                    <td class="ps-4 text-nowrap">
                        <div class="fw-bold text-dark">{{ $issue->issued_at->format('Y-m-d') }}</div>
                    </td>

                    {{-- PRACOWNIK --}}
                    <td>
                        <div class="d-flex align-items-center">
                            <div>
                                <div class="fw-bold">
                                    {{ $issue->employee->last_name }} {{ $issue->employee->first_name }}
                                </div>
                                <div class="small text-muted">
                                    {{ $issue->employee->position->name ?? 'Brak stanowiska' }}
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- PRODUKT --}}
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $issue->batch->product->image_url }}" 
                                class="rounded border me-2" 
                                style="width: 40px; height: 40px; object-fit: cover;">

                            <div>
                                <a href="{{ route('items.show', $issue->batch->product->id) }}" class="text-decoration-none fw-bold text-dark">
                                    {{ $issue->batch->product->name }}  
                                </a>
                                <div class="small text-muted">Rozmiar: {{ $issue->batch->size }} | {{ $issue->quantity }} szt.</div>
                            </div>
                        </div>
                    </td>

                    {{-- DATA WAŻNOŚCI (Z KOLOROWANIEM) --}}
                    <td>
                        @if($issue->due_date)
                            @php
                                // Obliczamy różnicę dni
                                $daysLeft = now()->diffInDays($issue->due_date, false); 
                                // false oznacza, że jak data minęła, wynik będzie ujemny (np. -5)
                                
                                $isOverdue = $daysLeft < 0; // Data minęła
                                $isDueSoon = $daysLeft >= 0 && $daysLeft <= 30; // Zostało mniej niż 30 dni
                            @endphp

                            @if($isOverdue)
                                {{-- CZERWONY: PO TERMINIE --}}
                                <div class="text-danger fw-bold animate__animated animate__pulse animate__infinite">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $issue->due_date->format('Y-m-d') }}
                                </div>
                                <span class="badge bg-danger">PO TERMINIE!</span>
                            
                            @elseif($isDueSoon)
                                {{-- ŻÓŁTY: OSTRZEŻENIE (do 30 dni) --}}
                                <div class="fw-bold" style="color: #d39e00;"> {{-- Ciemny żółty dla czytelności --}}
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $issue->due_date->format('Y-m-d') }}
                                </div>
                                <span class="badge bg-warning text-dark">Wygasa za {{ (int)$daysLeft }} dni</span>
                            
                            @else
                                {{-- ZIELONY: BEZPIECZNIE --}}
                                <div class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    {{ $issue->due_date->format('Y-m-d') }}
                                </div>
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    {{-- AKCJE --}}
                    <td class="text-end pe-4">
                        @if(!$issue->returned_at)
                            <button wire:click="archiveIssue({{ $issue->id }})" 
                                    wire:confirm="Czy na pewno chcesz zarchiwizować ten przedmiot? \n\nIlość wróci na stan magazynowy."
                                    class="btn btn-sm btn-outline-danger" 
                                    title="Zwróć i archiwizuj">
                                <i class="fas fa-undo me-1"></i> Zarchiwizuj
                            </button>
                        @else
                            <span class="text-success small"><i class="fas fa-check"></i> OK</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="fas fa-clipboard-list fa-3x mb-3 opacity-50"></i><br>
                        Brak historii wydań. Kliknij "Wydaj Towar", aby dodać pierwszy wpis.
                    </td>
                </tr>
            @endforelse
        </tbody>
        </table>
      <div class="card-footer bg-white border-0 py-3">
        {{ $activeIssues->links() }}
    </div> 
</div>

    <div class="accordion shadow-sm" id="accordionHistory">
        <div class="accordion-item border-0">
            <h2 class="accordion-header" id="headingHistory">
                <button class="accordion-button collapsed bg-light text-secondary fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHistory" aria-expanded="false" aria-controls="collapseHistory">
                    <i class="fas fa-history me-2"></i> Archiwum / Zwrócone ({{ $archivedIssues->total() }})
                </button>
            </h2>
            <div id="collapseHistory" class="accordion-collapse collapse" aria-labelledby="headingHistory" data-bs-parent="#accordionHistory" wire:ignore.self>
                <div class="accordion-body p-0">
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-muted">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Wydano</th>
                                    <th>Zwrócono</th>
                                    <th>Pracownik</th>
                                    <th>Produkt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($archivedIssues as $issue)
                                    <tr class="bg-light bg-opacity-50">
                                        <td class="ps-4 small">{{ $issue->issued_at->format('Y-m-d') }}</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $issue->returned_at->format('Y-m-d') }}
                                            </span>
                                        </td>
                                        <td class="small">
                                            {{ $issue->employee->last_name }} {{ $issue->employee->first_name }}
                                        </td>
                                        <td class="small">
                                            {{ $issue->batch->product->name }} ({{ $issue->quantity }} szt.)
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-3">Brak historii.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3">
                        {{ $archivedIssues->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('scroll-to-archive', () => {
        // Znajdź element akordeonu i przewiń do niego
        const element = document.getElementById('accordionHistory');
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
</script>
@endscript
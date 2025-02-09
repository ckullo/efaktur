<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Matcode;
use App\Models\MatcodeFile;

class MatcodeModal extends Component
{
    use WithPagination;

    public $matcodeFileId;

    protected $paginationTheme = 'tailwind';

    public function mount($matcodeFileId)
    {
        $this->matcodeFileId = $matcodeFileId;
    }

    public function render()
    {
        $matcodeFile = MatcodeFile::find($this->matcodeFileId);
        $matcodes = Matcode::where('id_m_matcode_file', $this->matcodeFileId)
                            ->paginate(25);

        return view('livewire.matcode-modal', compact('matcodeFile', 'matcodes'));
    }
}

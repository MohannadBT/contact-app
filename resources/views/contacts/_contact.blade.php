<tr>
    {{-- (if we use @each) --}}
    <th scope="row">{{ $contacts->firstItem() + $index }}</th>

    {{-- <th scope="row">{{ $loop->index }}</th>  --}}
    <td>{{ $contact->first_name }}</td>
    <td>{{ $contact->last_name }}</td>
    <td>{{ $contact->email }}</td>
       <td>{{ $contact->company->name }}</td>
    <td width="150">
        <a href="{{ route('contacts.show', $contact->id) }}" class="btn btn-sm btn-circle btn-outline-info"
            title="Show"><i class="fa fa-eye"></i></a>
        <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-sm btn-circle btn-outline-secondary" title="Edit"><i
                class="fa fa-edit"></i></a>
        <form action="{{ route('contact.destroy', $contact->id) }}" method="POST" style="display: inline">
            @csrf
            @method('delete')
            <botton type="submit" class="btn btn-sm btn-circle btn-outline-danger" title="Delete">
                <i class="fa fa-trash"></i></botton>
        </form>
    </td>
</tr>

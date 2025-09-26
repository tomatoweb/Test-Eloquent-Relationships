<ul>
    @forelse ($tasks as $task)
        <li>{{ $task->name }} ({{ $task->user?->name ?? 'unknown user' }})</li>
    @empty
        <p>No tasks</p>
    @endforelse
</ul>

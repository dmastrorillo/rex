export type TableHeadProps = {
    columns: string[];
};

export function TableHead({ columns }: TableHeadProps) {
    return (
        <thead className="border-sidebar-border/70 dark:border-sidebar-border border-b bg-gray-50 dark:bg-gray-800">
            <tr>
                {columns.map((column) => (
                    <th key={column}>{column}</th>
                ))}
            </tr>
        </thead>
    );
}

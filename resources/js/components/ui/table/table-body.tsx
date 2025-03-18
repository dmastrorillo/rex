import { GenericTableData, GetKeyOf } from './types';

export type TableBodyProps<T extends GenericTableData[]> = {
    data: T;
    columns: GetKeyOf<T>[];
};

export function TableBody<T extends GenericTableData[]>({ data, columns }: TableBodyProps<T>) {
    return (
        <tbody>
            {data.length === 0 ? (
                <tr>
                    <td colSpan={columns.length} className="h-[25vh] text-center">
                        <div className="text-2xl text-gray-500 dark:text-gray-400">No data found...</div>
                    </td>
                </tr>
            ) : (
                <>
                    {data.map((row, index) => (
                        <tr key={row.id ?? index}>
                            {columns.map((column) => (
                                <td key={String(column)}>{row[column]}</td>
                            ))}
                        </tr>
                    ))}
                </>
            )}
        </tbody>
    );
}

import { cn } from '@/lib/utils';
import { GenericTableData, GetKeyOf } from './types';

export type TableBodyProps<T extends GenericTableData[]> = {
    data: T;
    columns: GetKeyOf<T>[];
    onRowClick?: (rowData: T[number], index: number) => void;
};

export function TableBody<T extends GenericTableData[]>({ data, columns, onRowClick }: TableBodyProps<T>) {
    const isRowClickable = Boolean(onRowClick);
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
                        <tr className={cn(isRowClickable && 'hover:bg-secondary cursor-pointer')} key={row.id ?? index} onClick={() => onRowClick?.(row, index)}>
                            {columns.map((column) => (
                                <td key={String(column)} className="text-center">
                                    <div className="p-2">{row[column]}</div>
                                </td>
                            ))}
                        </tr>
                    ))}
                </>
            )}
        </tbody>
    );
}

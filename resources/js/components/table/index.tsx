import { TableBody } from './TableBody';
import { TableHead } from './TableHead';
import { ColumnInfo, GenericTableData } from './types';



export type TableProps<T extends GenericTableData[]> = {
    data: T;
    /**
     * Key-value mapping the column data to the display name
     */
    columnInfo: ColumnInfo<T>;
};

export function Table<T extends GenericTableData[]>({ data, columnInfo }: { data: T; columnInfo: ColumnInfo<T> }) {
    return (
        <table className="w-full">
            <TableHead columns={Object.values(columnInfo).filter(Boolean) as string[]} />
            <TableBody data={data} columns={Object.keys(columnInfo)} />
        </table>
    );
}
